import { nextui } from "@nextui-org/react"

import type { Config } from "tailwindcss"

const config: Config = {
  content: [
    "./src/pages/**/*.{js,ts,jsx,tsx,mdx}",
    "./src/components/**/*.{js,ts,jsx,tsx,mdx}",
    "./src/app/**/*.{js,ts,jsx,tsx,mdx}",
    "./node_modules/@nextui-org/theme/dist/**/*.{js,ts,jsx,tsx}"
  ],
  theme: {
    extend: {
      backgroundImage: {
        "gradient-radial": "radial-gradient(var(--tw-gradient-stops))",
        "gradient-conic":
          "conic-gradient(from 180deg at 50% 50%, var(--tw-gradient-stops))"
      },
      spacing: {
        '128': '30rem',
      }
    }, screens: {
      sm: '480px',
      md: '768px',
      "2md": '850px',
      lg: '976px',
      "2lg": '1200px',
      xl: '1440px',
      "2xl": '1580px',
      "3xl": '1680px'
    }
  },
  darkMode: "class",
  plugins: [nextui()]
}

export default config