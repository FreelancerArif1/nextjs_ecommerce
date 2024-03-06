import Slider from 'react-slick'
import Image from 'next/image'

import 'slick-carousel/slick/slick.css'
import 'slick-carousel/slick/slick-theme.css'

export default function CategoryWise() {

    const settings = {
        dots: true,
        infinite: true,
        speed: 500,
        slidesToShow: 5,
        slidesToScroll: 1,
        initialSlide: 5,
        autoplay: true,
        pauseOnHover: true,
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 3,
              infinite: true,
              dots: true
            }
          },
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2,
              initialSlide: 2
            }
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1
            }
          }
        ]
      }

  const hotelCards = [
    {
      imageSrc:
        '/assets/images/vegetable/banner/4.jpg',
      title: 'Studio Room',
      description: 'Lorem ipsum dolor sit amet, consectur dolori',
      pricingText: 'USD 50/Day',
      features: ['Free Wifi', 'Free breakfast'],
    },

    {
        imageSrc:
        '/assets/images/vegetable/banner/5.jpg',
        title: 'Deluxe Room',
        description: 'Lorem ipsum dolor sit amet, consectur dolori',
        pricingText: 'USD 80/Day',
        features: ['Free Wifi', 'Free breakfast'],
      },
    {
      imageSrc:
      '/assets/images/vegetable/banner/6.jpg',title: 'Deluxe Room',
      description: 'Lorem ipsum dolor sit amet, consectur dolori',
      pricingText: 'USD 80/Day',
      features: ['Free Wifi', 'Free breakfast'],
    },
    {
        imageSrc:
        '/assets/images/vegetable/banner/7.jpg',title: 'Deluxe Room',
        description: 'Lorem ipsum dolor sit amet, consectur dolori',
        pricingText: 'USD 80/Day',
        features: ['Free Wifi', 'Free breakfast'],
      },
    {
      imageSrc:
      '/assets/images/vegetable/banner/4.jpg',title: 'King Deluxe Room',
      description: 'Lorem ipsum dolor sit amet, consectur dolori',
      pricingText: 'USD 150/Day',
      features: ['Free Wifi', 'Free breakfast', 'Discounted Meals'],
    },
    {
      imageSrc:
      '/assets/images/vegetable/banner/5.jpg',title: 'Royal Suite',
      description: 'Lorem ipsum dolor sit amet, consectur dolori',
      pricingText: 'USD 299/Day',
      features: [
        'Free Wifi',
        'Free breakfast',
        'Discounted Meals',
        "MacBook for work use (hotel's property)",
      ],
    },
    {
        imageSrc:
        '/assets/images/vegetable/banner/6.jpg',title: 'Deluxe Room',
        description: 'Lorem ipsum dolor sit amet, consectur dolori',
        pricingText: 'USD 80/Day',
        features: ['Free Wifi', 'Free breakfast'],
      },
  ]


  return (
    <div className='content'>
      <Slider {...settings}>
        {hotelCards.map((card, index) => (
          <div key={index}>
                <a href="shop-left-sidebar.html" className="category-box category-dark">
                    <div>
                        <Image src="https://themes.pixelstrap.com/fastkart/assets/svg/1/vegetable.svg" className="blur-up lazyload" alt="" width={43} height={43}/>
                        <h5>Vegetables & Fruit</h5>
                    </div>
                </a>
          </div>
        ))}
      </Slider>

    </div>
  )
}