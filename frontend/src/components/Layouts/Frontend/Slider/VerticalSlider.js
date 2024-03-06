import Slider from 'react-slick'

import 'slick-carousel/slick/slick.css'
import 'slick-carousel/slick/slick-theme.css'

export default function Carousel() {

    const settings = {
        dots: false,
        arrows: false,
        pauseOnHover: true,
        infinite: true,
        speed: 500,
        slidesToShow: 1,
        slidesToScroll: 1,
        initialSlide: 1,
        autoplay: true,
        vertical: true,
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
      title: 'Something you love is now on sale! 1',
      linkText: 'wwww.facebook.com',
      whiteText: 'Buy Now',
    },
    {
      title: 'Something you love is now on sale! 2',
      linkText: 'wwww.facebook.com',
      whiteText: 'Buy Now',
    },
    {
        title: 'Something you love is now on sale! 3',
        linkText: 'wwww.facebook.com',
        whiteText: 'Buy Now',
    },
  ]


  return (
    <div className='content'>
      <Slider {...settings}>
        {hotelCards.map((card, index) => (
          <div key={index}>
            <div className="timer-notification">
                <h6 className='m-0'>{card.title} <a href={card.linkText}  className="text-white">{card.whiteText} !</a></h6>
            </div>
          </div>
        ))}
      </Slider>

    </div>
  )
}