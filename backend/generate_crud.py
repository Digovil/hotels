import os
import re
import textwrap

# Función para convertir a formato PascalCase
def to_pascal_case(s):
    return ''.join(x.capitalize() for x in re.split(r'[^a-zA-Z0-9]', s) if x)

# Función para convertir a formato snake_case
def to_snake_case(s):
    s = re.sub('(.)([A-Z][a-z]+)', r'\1_\2', s)
    return re.sub('([a-z0-9])([A-Z])', r'\1_\2', s).lower()

# Función para obtener el tipo de dato y tamaño del field
def get_field_data(field):
    match = re.match(r"\$table->(\w+)\('([^']+)'\s*(?:,\s*'(\d+)')?\);", field)
    if match:
        data_type, field_name, field_size = match.groups()
        if data_type == 'uuid':
            return 'primary_key', field_name, None
        elif data_type == 'foreign':
            return 'foreign_key', field_name, None
        elif data_type != 'foreignUuid':
            return data_type, field_name, field_size
    return None

# Función para generar el model
def generate_model(table_name, primary_key, fields):
    model_name = to_pascal_case(table_name)
    snake_case_table_name = to_snake_case(table_name)
    
    model_code = textwrap.dedent(f"""\
    <?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Str;

    class {model_name} extends Model
    {{
        use HasFactory;
        public $incrementing = false;
        protected $keyType = 'string';  
        protected $primaryKey = '{primary_key}';
        const CREATED_AT = 'created_at_{snake_case_table_name}';
        const UPDATED_AT = 'updated_at_{snake_case_table_name}';

        protected $fillable = [
            '{primary_key}',
            {', '.join([f"'{field[1]}'" for field in fields])}
        ];

        protected $guarded = ['{primary_key}', 'created_at_{snake_case_table_name}', 'updated_at_{snake_case_table_name}'];

        protected static function boot()
        {{
            parent::boot();
            static::creating(function ($model) {{
                $model->{snake_case_table_name}_id = (string) Str::uuid();
            }});
        }}
    }}
    """)

    return model_code
# Función para generar el controlador
import textwrap

def generate_controller(table_name, fields, folder_name):
    model_name = to_pascal_case(table_name)

    controller_code = textwrap.dedent(f"""\
    <?php

    namespace App\Http\Controllers\\{folder_name}\\{model_name};

    use App\Http\Controllers\Controller;
    use App\Models\\{model_name};
    use App\Http\Resources\\{folder_name}\\{model_name}\\{model_name}Resource;
    use App\Services\ResponseFormatService;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    class {model_name}Controller extends Controller
    {{
        protected $responseFormat;

        public function __construct(ResponseFormatService $responseFormat)
        {{
            $this->responseFormat = $responseFormat;
        }}

        public function index(Request $request)
        {{
            try {{
                $limit = $request->input('limit', 10);
                ${model_name.lower()} = {model_name}Resource::collection({model_name}::get())->paginate($limit);
                return $this->responseFormat->success(${model_name.lower()}, "Mostrando {model_name.lower()} disponibles", 200);
            }} catch (\\Exception $e) {{
                return $this->responseFormat->error("Error en el servidor " . $e->getMessage());
            }}
        }}

        public function store(Request $request)
        {{
            try {{
                $validator = Validator::make($request->all(), [
                    {', '.join([f"'{field[1]}' => '{to_snake_case(field[0])}'" for field in fields])}
                ]);

                if ($validator->fails()) {{
                    return $this->responseFormat->error("Error al create {model_name.lower()} ".$validator->errors(), 422);
                }}

                ${model_name.lower()} = {model_name}::create([
                    {', '.join([f"'{field[1]}' => $request['{to_snake_case(field[1])}']" for field in fields])}
                ]);

                ${model_name.lower()}->save();

                return $this->responseFormat->success(new {model_name}Resource(${model_name.lower()}), '{model_name} creado exitosamente', 201);
            
            }} catch (\\Exception $e) {{
                return $this->responseFormat->error("Error en el servidor " . $e->getMessage());
            }}
        }}

        public function show($id)
        {{
            try {{
                $validator = Validator::make(['id' => $id], [
                    'id' => 'required|uuid',
                ]);

                if ($validator->fails()) {{
                    return $this->responseFormat->error("Error en la consulta ".$validator->errors(), 422);
                }}

                $respuesta = {model_name}::orWhere('{to_snake_case(model_name)}_id', $id)->first();

                if (!$respuesta) {{
                    return $this->responseFormat->error("{model_name} no encontrado", 404);
                }}

                return $this->responseFormat->success(new {model_name}Resource($respuesta), 'Mostrando {model_name.lower()}', 200);

            }} catch (\\Exception $e) {{
                return $this->responseFormat->error("Error en el servidor " . $e->getMessage());
            }}
        }}

        public function update(Request $request, $id)
        {{
            try {{
                $validator = Validator::make(['id' => $id], [
                    'id' => 'required|uuid',
                ]);

                if ($validator->fails()) {{
                    return $this->responseFormat->error("Error en la consulta ".$validator->errors(), 422);
                }}

                $validator = Validator::make($request->all(), [
                    {', '.join([f"'{field[1]}' => '{to_snake_case(field[0])}'" for field in fields])}
                ]);

                $respuesta = {model_name}::orWhere('{to_snake_case(model_name)}_id', $id)->first();

                if ($respuesta == null) {{
                    return $this->responseFormat->error("No encontrado", 404);
                }}

                $respuesta->update([
                    {', '.join([f"'{field[1]}' => $request['{to_snake_case(field[1])}']" for field in fields])}
                ]);
                
                return $this->responseFormat->success(new {model_name}Resource($respuesta), '{model_name} actualizado exitosamente', 200);

            }} catch (\\Exception $e) {{
                return $this->responseFormat->error("Error en el servidor " . $e->getMessage());
            }}
        }}

        public function destroy($id)
        {{
            try {{
                $validator = Validator::make(['id' => $id], [
                    'id' => 'required|uuid',
                ]);

                if ($validator->fails()) {{
                    return $this->responseFormat->error("Error en la consulta ".$validator->errors(), 422);
                }}

                ${model_name.lower()} = {model_name}::find($id);

                if (!${model_name.lower()}) {{
                    return $this->responseFormat->error("{model_name} no encontrado", 404);
                }}

                ${model_name.lower()}->delete();

                return $this->responseFormat->success(null, '{model_name} eliminado con éxito', 200);
            }} catch (\\Exception $e) {{
                return $this->responseFormat->error("Error en el servidor " . $e->getMessage());
            }}
        }}
    }}
    """)

    return controller_code

# Función para generar el recurso
import textwrap

def generate_resource(table_name, fields, folder_name):
    model_name = to_pascal_case(table_name)
    snake_case_table_name = to_snake_case(table_name)

    resource_code = textwrap.dedent(f"""\
    <?php

    namespace App\Http\Resources\\{folder_name}\\{model_name};

    use Illuminate\Http\Resources\Json\JsonResource;
    use Carbon\Carbon;

    class {model_name}Resource extends JsonResource
    {{
        /**
         * Transform the resource into an array.
         *
         * @param  \\Illuminate\Http\Request  $request
         * @return array|\\Illuminate\Contracts\Support\Arrayable|\\JsonSerializable
         */
        public function toArray($request)
        {{
            return [
                'type' => '{snake_case_table_name}',
                'id' => $this->resource->{snake_case_table_name}_id,
                'attribute' => [
                    {','.join([f"'{field[1]}' => $this->resource->{field[1]}" for field in fields])}
                    'created_at_{snake_case_table_name}' => Carbon::createFromFormat('Y-m-d H:i:s', $this->resource->created_at_{snake_case_table_name})->format('d/m/Y H:i'),
                ]
            ];
        }}
    }}
    """)

    return resource_code

def convertir_a_namespace(input_str):
    # Reemplazar '/' con '\'

    result_str = input_str.replace('/', """\ """)
    result_str = result_str.replace(' ', '')

    # Quitar la extensión ".php" si está presente
    result_str = result_str.replace('.php', '')

    # Cambiar "app" por "App"
    result_str = result_str.replace('app', 'App', 1)

    return result_str

def generate_routes(path_controller, model_name):

    model_name  = to_snake_case(model_name)
    route_code = f"""<?php

use Illuminate\Support\Facades\Route;

Route::get('{model_name}/{{id}}', [{path_controller}::class, 'show'])->name('api.v1.{to_snake_case(model_name)}.show');
Route::get('{model_name}', [{path_controller}::class, 'index'])->name('api.v1.{to_snake_case(model_name)}.index');
Route::post('{model_name}/create', [{path_controller}::class, 'store'])->name('api.v1.{to_snake_case(model_name)}.store');
Route::put('{model_name}/update/{{id}}', [{path_controller}::class, 'update'])->name('api.v1.{to_snake_case(model_name)}.update');
Route::delete('{model_name}/delete/{{id}}', [{path_controller}::class, 'destroy'])->name('api.v1.{to_snake_case(model_name)}.destroy');

    """

    return route_code

# # Directorio de los archivos PHP
# directory_path = 'database/migrations'

# # Obtener la lista de archivos en el directorio
# file_list = [f for f in os.listdir(directory_path) if f.endswith('.php')]

# for file_name in file_list:
migration_file = input('Ingrese la ruta completa del archivo PHP de migración: ')

if os.path.exists(migration_file):
    
    # file_path = os.path.join(directory_path, file_name)

    # Leer el contenido del archivo
    with open(migration_file, 'r') as file:
        content = file.read()

        # Obtener el name de la tabla
        table_match = re.search(r"Schema::create\('([^']+)'", content)
        if table_match:
            table_name = table_match.group(1)

            # Obtener la clave primaria
            primary_key_match = re.search(r"\$table->id\('([^']+)'", content)
            primary_key = primary_key_match.group(1) if primary_key_match else None

            # Obtener los fields y sus tipos
            fields = []
            foreign_keys = []

            field_matches = re.finditer(r"\$table->(\w+)\('([^']+)',?\s?(\d*)\)", content)
            for match in field_matches:
                data_type, field_name, field_size = match.groups()
                if data_type == 'uuid':
                    primary_key = field_name
                elif data_type == 'foreign':
                    foreign_keys.append((field_name, re.search(r"->on\('([^']+)'", content).group(1)))
                else:
                    fields.append((data_type, field_name, field_size))

            # Ordenar las claves foráneas por orden de aparición
            foreign_keys.sort(key=lambda x: content.find(f"->references('{x[0]}')"))

            # Generar el código del model
            model_code = generate_model(table_name, primary_key, fields)

            # Crear el archivo del model
            model_file_path = f'app/Models/{to_pascal_case(table_name)}.php'
            with open(model_file_path, 'w') as model_file:
                model_file.write(model_code)

            # Solicitar la carpeta contenedora para el controlador
            folder_name = input(f'Ingrese el name de la carpeta contenedora para el controlador de {table_name}: ')

            # Generar el código del controlador
            controller_code = generate_controller(table_name, fields, folder_name)
            resource_code = generate_resource(table_name, fields, folder_name)

            names_folder = f'app/Http/Controllers/{folder_name}/{to_pascal_case(table_name)}'
            names_folder_resource = f'app/Http/Resources/{folder_name}/{to_pascal_case(table_name)}'
            
            # Ruta para el nuevo directorio
            path_folder = os.path.join(os.getcwd(), names_folder)
            path_folder_resource = os.path.join(os.getcwd(), names_folder_resource)

            # Crear directorio Controller
            if not os.path.exists(path_folder):
                os.makedirs(path_folder)
                print(f'Directorio "{names_folder}" Controller creado.')
                
            if not os.path.exists(path_folder_resource):
                os.makedirs(path_folder_resource)
                print(f'Directorio "{names_folder_resource}" Resource creado.')
            
            # Crear el archivo del controlador
            controller_file_path = f'app/Http/Controllers/{folder_name}/{to_pascal_case(table_name)}/{to_pascal_case(table_name)}Controller.php'
            with open(controller_file_path, 'w') as controller_file:
                controller_file.write(controller_code)
                
            controller_file_path_resource = f'app/Http/Resources/{folder_name}/{to_pascal_case(table_name)}/{to_pascal_case(table_name)}Resource.php'
            with open(controller_file_path_resource, 'w') as controller_file:
                controller_file.write(resource_code)
            
            # Solicitar la carpeta contenedora para la ruta
            folder_name = input(f'Ingrese el name de la carpeta contenedora para la ruta de {table_name}: ')

            controller_file_path = convertir_a_namespace(controller_file_path)
            # Generar el código de las rutas
            routes_code = generate_routes(controller_file_path, table_name)

            names_folder = f'routes/{folder_name}/{to_pascal_case(table_name)}'

            # Ruta para el nuevo directorio
            path_folder = os.path.join(os.getcwd(), names_folder)
            
            # Crear directorio Routes
            if not os.path.exists(path_folder):
                os.makedirs(path_folder)
                print(f'Directorio "{names_folder}" Ruta creada.')
            
            
            # Crear el archivo del controlador
            routes_file_path = f'routes/{folder_name}/{to_pascal_case(table_name)}/{to_pascal_case(table_name)}Routes.php'
            with open(routes_file_path, 'w') as routes_file:
                routes_file.write(routes_code)

            print(f'Generados archivos para la tabla {table_name} en {model_file_path} y {controller_file_path}')
        else:
            print(f'No se encontró la instrucción Schema::create en el archivo')
