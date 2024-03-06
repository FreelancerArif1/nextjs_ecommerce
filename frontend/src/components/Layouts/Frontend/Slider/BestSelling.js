import Slider from 'react-slick'
import Image from 'next/image'

import 'slick-carousel/slick/slick.css'
import 'slick-carousel/slick/slick-theme.css'

export default function Carousel() {

    const settings = {
        dots: false,
        infinite: true,
        speed: 500,
        slidesToShow: 2,
        slidesToScroll: 1,
        initialSlide: 2,
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
              <ul className="product-list">
                  <li>
                      <div className="offer-product">
                          <a href="product-left-thumbnail.html" className="offer-image">
                              <Image src="/assets/images/vegetable/product/11.png"
                                  className="blur-up lazyload" width={90} height={40} alt=""/>
                          </a>

                          <div className="offer-detail">
                              <div>
                                  <a href="product-left-thumbnail.html" className="text-title">
                                      <h6 className="name">Tuffets Whole Wheat Bread</h6>
                                  </a>
                                  <span>500 G</span>
                                  <h6 className="price theme-color">$ 10.00</h6>
                              </div>
                          </div>
                      </div>
                  </li>

                  <li>
                      <div className="offer-product">
                          <a href="product-left-thumbnail.html" className="offer-image">
                              <Image src="/assets/images/vegetable/product/12.png"
                                  className="blur-up lazyload" width={90} height={40} alt=""/>
                          </a>

                          <div className="offer-detail">
                              <div>
                                  <a href="product-left-thumbnail.html" className="text-title">
                                      <h6 className="name">Potato</h6>
                                  </a>
                                  <span>500 G</span>
                                  <h6 className="price theme-color">$ 10.00</h6>
                              </div>
                          </div>
                      </div>
                  </li>

                  <li>
                      <div className="offer-product">
                          <a href="product-left-thumbnail.html" className="offer-image">
                              <Image src="/assets/images/vegetable/product/13.png"
                                  className="blur-up lazyload" width={90} height={40} alt=""/>
                          </a>

                          <div className="offer-detail">
                              <div>
                                  <a href="product-left-thumbnail.html" className="text-title">
                                      <h6 className="name">Green Chilli</h6>
                                  </a>
                                  <span>200 G</span>
                                  <h6 className="price theme-color">$ 10.00</h6>
                              </div>
                          </div>
                      </div>
                  </li>

                  <li>
                      <div className="offer-product">
                          <a href="product-left-thumbnail.html" className="offer-image">
                              <Image src="/assets/images/vegetable/product/14.png"
                                  className="blur-up lazyload" width={90} height={40} alt=""/>
                          </a>

                          <div className="offer-detail">
                              <div>
                                  <a href="product-left-thumbnail.html" className="text-title">
                                      <h6 className="name">Muffets Burger Bun</h6>
                                  </a>
                                  <span>150 G</span>
                                  <h6 className="price theme-color">$ 10.00</h6>
                              </div>
                          </div>
                      </div>
                  </li>
              </ul>
          </div>
        ))}
      </Slider>

    </div>
  )
}