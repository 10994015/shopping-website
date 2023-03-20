/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      keyframes:{
        'fade-in-down':{
          "from":{
            transform:"translare(-0.75ren)",
            opacity: '0'
          },
          "to":{
            transform:"translare(0)",
            opacity: '1'
          }
        }
      },
      animation:{
        'fade-in-down':"fade-in-down 0.2s ease-in-out both"
      }
    },
  },
  plugins: [],
}
