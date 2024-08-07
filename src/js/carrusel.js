document.addEventListener('DOMContentLoaded', () => {
    const swiperContainer = document.querySelector('.swiper-container');
    
    if (swiperContainer) {
        const swiper = new Swiper(swiperContainer, {
            loop: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 1,
                    spaceBetween: 5,
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 5,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 5,
                },
                1200: {
                    slidesPerView: 4,
                    spaceBetween: 5,
                },
            },
        });
    } else {
        console.error('Elemento con clase "swiper-container" no encontrado.');
    }
});
