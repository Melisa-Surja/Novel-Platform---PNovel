const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
  future: {
    // removeDeprecatedGapUtilities: true,
    // purgeLayersByDefault: true,
  },
  purge: [],
  theme: {
    themeVariants: ['dark'],
    extend: {
      fontSize: {
        '2xs': '.65rem',
      },
      colors: {
        header: 'var(--color-header)',
        footer: 'var(--color-footer)',
        'primary-light': 'var(--color-primary-light)',
        primary: 'var(--color-primary)',
        'secondary-light': 'var(--color-secondary-light)',
        secondary: 'var(--color-secondary)',
        'bg-light': 'var(--color-bg-light)',
        bg: 'var(--color-bg)',
        'bg-dark': 'var(--color-bg-dark)',
      },
      fontFamily: {
        sans: ['Nunito', ...defaultTheme.fontFamily.sans],
      },
      opacity: {
        '5': '.05',
        '10': '.1',
        '20': '.2',
        '30': '.3',
        '40': '.4',
        '50': '.5',
        '60': '.6',
        '70': '.7',
        '80': '.8',
        '90': '.9',
      },
    },
  },
  variants: {
    backgroundColor: ['responsive', 'hover', 'focus', 'dark'],
    textColor: ['responsive', 'hover', 'focus', 'dark'],
  },
  plugins: [
    require('tailwindcss-multi-theme'),
    require('@tailwindcss/ui'),
  ],
}
