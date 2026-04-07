/** @type {import('next').NextConfig} */
const withPWA = require('next-pwa')({
  dest: 'public', // папка, куда будет скопирован сервис-воркер
  register: true,
  skipWaiting: true,
  disable: process.env.NODE_ENV === 'development', // отключаем PWA в режиме разработки
});

const nextConfig = {
  reactStrictMode: true,
  // другие настройки
};

module.exports = withPWA(nextConfig);