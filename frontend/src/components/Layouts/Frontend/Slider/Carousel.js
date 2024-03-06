import Slider from 'react-slick'
import Image from 'next/image'
import Link from 'next/link';
import 'slick-carousel/slick/slick.css'
import 'slick-carousel/slick/slick-theme.css'

export default function Carousel({all_slider}) {

    const settings = {
        dots: true,
        infinite: true,
        speed: 500,
        slidesToShow: 1,
        slidesToScroll: 1,
        initialSlide: 1,
        autoplay: true,
        pauseOnHover: true,
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
              infinite: true,
              dots: true
            }
          },
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
              initialSlide: 1
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




  return (
    <div className='content'>
      <Slider {...settings}>
        {all_slider?.map((slider, index) => (
          <div key={index} className="home-contain h-100 vh60">
              <div className="h-100">
                  <Image src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/${slider.image}`} className="bg-img blur-up lazyload"  width={1155} height={650} alt="" />
              </div>
              <div className="home-detail p-center-left w-75">
                  <div>
                      <h6>
                        {/* Exclusive offer <span>30% Off</span> */}
                      </h6>
                      <h1 className="text-uppercase">
                        {/* Stay home & delivered your <span className="daily">Daily
                              Needs</span> */}
                              
                              </h1>
                      <p className="w-75 d-none d-sm-block">
                        {/* Vegetables contain many vitamins and minerals that are
                          good for your health. */}
                          </p>
                      
                      {slider.button_link && slider.button_title ? (
                      <Link href={`${slider.button_link}`} > <button className="btn btn-animation text-light mt-xxl-4 mt-2 home-button mend-auto">{slider.button_title} <i
                              className="fa-solid fa-right-long icon"></i></button></Link>
                      
                      ):('')}
                  </div>
              </div>
          </div>

        ))}
      </Slider>

    </div>
  )
}