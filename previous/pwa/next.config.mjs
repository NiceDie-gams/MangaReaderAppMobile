import withPWA from 'next-pwa';

const pwaConfig = withPWA({
  dest: 'public',
  register: true,
  skipWaiting: true,
  disable: process.env.NODE_ENV === 'development',
});

/** @type {import('next').NextConfig} */
const nextConfig = {
  turbopack: {
    // пустой объект, чтобы отключить встроенную конфигурацию Turbopack
  },
  // Если нужно явно указать использование webpack:
  // webpack: (config, options) => {
  //   return config;
  // },
};

export default pwaConfig(nextConfig);