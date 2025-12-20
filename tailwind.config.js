/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/js/**/*.{js,jsx,ts,tsx,vue}', // scan React/TS files inside resources/js
    './resources/views/**/*.blade.php', // scan Blade templates (optional)
  ],
  theme: {
    extend: {
      colors: {
        'primary-purple': '#A855F7', // Button gradient color (approximated)
        'secondary-purple': '#C084FC', // Lighter purple for gradient
        'bg-light-purple': '#F3E8FF', // Background gradient start
        'bg-light-pink': '#FCE7F3', // Background gradient end
        'text-dark': '#1F2937', // Dark text for headings
        'text-gray': '#6B7280', // Lighter text for descriptions
      },
      // Define the gradient background
      backgroundImage: {
        'gradient-pastel': 'linear-gradient(90deg, #F3E8FF 0%, #FCE7F3 100%)', // Gradient from the image
        'gradient-button': 'linear-gradient(90deg, #A855F7 0%, #C084FC 100%)', // Button gradient
      },
      // Define typography
      fontFamily: {
        sans: ['Inter', 'sans-serif'], // Clean, modern font similar to the one in the image
      },
      // Define button styles
      borderRadius: {
        'xl': '1rem', // For rounded buttons
      },
    },
  },
  plugins: [],
}
