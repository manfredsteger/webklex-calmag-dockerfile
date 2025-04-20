const colors = require('tailwindcss/colors');

delete colors['lightBlue'];
delete colors['warmGray'];
delete colors['trueGray'];
delete colors['coolGray'];
delete colors['blueGray'];

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/views/*.{phtml,php}",
    "./resources/**/*.{phtml,php}",
  ],
  darkMode: 'class',
  theme: {
    colors: {
      ...colors,
    },
    extend: {},
  },
  plugins: [],
  safelist: [
    "text-teal-500",
    "dark"
  ]
}
