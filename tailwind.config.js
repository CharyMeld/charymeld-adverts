export default {
  darkMode: 'class',
  content: ['./resources/views/**/*.blade.php', './resources/js/**/*.vue'],
  future: {
    hoverOnlyWhenSupported: true,
  },
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#f0f9f4',
          100: '#daf0e3',
          200: '#b3e0c6',
          300: '#80cb9f',
          400: '#4db378',
          500: '#2E6F40',
          600: '#265a35',
          700: '#1e452a',
          800: '#16311f',
          900: '#0e1c13',
        },
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
      },
      boxShadow: {
        'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
        'glow': '0 0 20px rgba(14, 165, 233, 0.4)',
      },
    },
  },
  plugins: [],
}
