/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    fontFamily: {
      sans : ['Jost']
    },
    extend: {
      colors: {
        'gray-muni' : '#f3f5f7',
        'blue-muni' : '#000f9e',
        'lime-muni' : '#93d500',
        'sky-muni'  : '#54c8e8',
        'green-muni': '#00bc70',
        'red-muni'  : '#f5333f',
      }
    },
  },
  plugins: [
    require('tailwindcss-animated')
  ],
}

