<script src="/public/js/tailwind.js"></script>
<script>
    tailwind.config = {
        theme: {
            container: {
                center: true,
                padding: "2rem",
                screens: {
                    "2xl": "1400px",
                    "3xl": "1600px",
                    "4xl": "1800px",
                    "5xl": "2000px",
                },
            },
            extend: {
                colors: {
                    primary: {
                        0: '#FFFFFF',
                        100: '#EEF3FA',
                        200: '#DCE7F5',
                        300: '#CADCF1',
                        400: '#B9D0EC',
                        500: '#A6C5E7',
                        600: '#93BAE2',
                        700: '#80AFDD',
                        800: '#6AA4D8',
                        900: '#5299D3'
                    },
                    dark: {
                        100: '#737373',
                        200: '#666666',
                        300: '#4d4d4d',
                        400: '#404040',
                        500: '#333333',
                        600: '#262626',
                        700: '#1a1a1a',
                        800: '#0d0d0d',
                        900: '#000000'
                    }
                }
            }
        }
    }
</script>
<link rel="icon" type="image/jpeg" href="/public/img/logos/logo_sans_fond.png">


