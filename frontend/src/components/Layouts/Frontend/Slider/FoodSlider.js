import Slider from 'react-slick'
import Image from 'next/image'

import 'slick-carousel/slick/slick.css'
import 'slick-carousel/slick/slick-theme.css'
import SingleProduct from "@/components/SingleProduct";


export default function Carousel({items}) {

    const settings = {
        dots: false,
        infinite: true,
        speed: 500,
        slidesToShow: 3,
        slidesToScroll: 1,
        initialSlide: 3,
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


  return (
    <div className='content'>
      <Slider {...settings}>
        {items?.map((data, index) => (
          <div key={index}>

            <div className="row m-0">
                <div className="col-12 px-0">
                  <SingleProduct data={data}></SingleProduct>
                </div>
            </div>

          </div>
        ))}
      </Slider>

    </div>
  )
}