// src/app/layout.tsx
'use client';

import { NextUIProvider } from '@nextui-org/react';
import { Inter } from 'next/font/google';
import Sidebar from '../components/layout/Sidebar';
import './globals.css';

const inter = Inter({ subsets: ['latin'] });

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="es" className='light'>
      <body className={inter.className}>
        <NextUIProvider>
          <div className="min-h-screen flex">
            <Sidebar />
            <main className="flex-1">
              {children}
            </main>
          </div>
        </NextUIProvider>
      </body>
    </html>
  );
}