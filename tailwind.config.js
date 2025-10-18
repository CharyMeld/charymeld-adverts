export default {
  darkMode: 'class',
  content: ['./resources/views/**/*.blade.php', './resources/js/**/*.vue'],
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#f0fdf4',
          100: '#dcfce7',
          200: '#bbf7d0',
          300: '#86efac',
          400: '#4ade80',
          500: '#2E6F40',
          600: '#2E6F40',
          700: '#236030',
          800: '#1a4a24',
          900: '#14381b',
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
