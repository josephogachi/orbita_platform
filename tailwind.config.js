import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                // Using 'Plus Jakarta Sans' for the modern, classic hospitality feel
                sans: ['Plus Jakarta Sans', 'Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // YOUR CUSTOM BRAND COLORS
                orbita: {
                    blue: '#0F2C59',  // The Navy Blue
                    gold: '#C68E59',  // The Copper/Gold
                    light: '#F8FAFC', // Very light blue-gray for section backgrounds
                    dark: '#0B2042',  // Deep Navy for footer
                    50: '#f0f4f9',    
                    900: '#081a36',   
                },
                // Mapping 'primary' to your Blue for consistency across Filament and Frontend
                primary: {
                    50: '#f0f4f9',
                    100: '#dbeafe',
                    500: '#3b82f6',
                    600: '#0F2C59', // Main brand color
                    700: '#0b2042',
                }
            },
            borderRadius: {
                '4xl': '2.5rem',
                '5xl': '3.5rem',
            },
            boxShadow: {
                '3xl': '0 35px 60px -15px rgba(15, 44, 89, 0.3)', // Custom blue-tinted shadow
                'card': '0 20px 40px -15px rgba(15, 44, 89, 0.15)', // Soft shadow for product cards
                'glow': '0 0 20px rgba(198, 142, 89, 0.5)', // Gold glow effect
            },
            animation: {
                'marquee': 'marquee 40s linear infinite',
                'float': 'float 6s ease-in-out infinite', // For the abstract shapes
                'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            },
            keyframes: {
                marquee: {
                    '0%': { transform: 'translateX(0%)' },
                    '100%': { transform: 'translateX(-50%)' }, // Seamless loop
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-20px)' },
                }
            }
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};