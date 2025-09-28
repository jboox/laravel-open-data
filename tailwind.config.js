module.exports = {
  mode: 'jit',
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./storage/framework/views/*.php",
    "./resources/**/*.ts",
  ],
  safelist: [
    'rounded',
    'rounded-lg',
    'rounded-full',
    'px-4',
    'py-3',
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}
