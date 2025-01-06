/* eslint-disable @typescript-eslint/no-explicit-any */
'use client';

import React from 'react';
import { Building2, BedDouble } from 'lucide-react';
import Link from 'next/link';
import { usePathname } from 'next/navigation';

export default function Sidebar() {
  const pathname = usePathname();

  const menuItems = [
    {
      title: 'Hoteles',
      icon: <Building2 size={24} />,
      path: '/hotels'
    },
    {
      title: 'Habitaciones',
      icon: <BedDouble size={24} />,
      path: '/hotel-rooms'
    }
  ];

  return (
    <div className="w-64 min-h-screen bg-gray-900 text-white flex-shrink-0">
      <div className="p-6 border-b border-gray-800">
        <h1 className="text-xl font-bold text-white">Hotel Admin</h1>
      </div>
      
      <nav className="mt-6">
        {menuItems.map((item) => (
          <Link
            key={item.path}
            href={item.path}
            className={`
              flex items-center gap-3 px-6 py-3 transition-colors
              ${pathname === item.path 
                ? 'bg-blue-600 text-white' 
                : 'text-gray-300 hover:bg-gray-800'
              }
            `}
          >
            {item.icon}
            <span>{item.title}</span>
          </Link>
        ))}
      </nav>
    </div>
  );
}