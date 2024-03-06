import Slider from 'react-slick'

import 'slick-carousel/slick/slick.css'
import 'slick-carousel/slick/slick-theme.css'
import Image from 'next/image'
import { useEffect, useState } from 'react'
import SingleProduct from "@/components/SingleProduct";
import { useDispatch } from 'react-redux';

// import { AddToCart, InitCart } from '../../../lib/features/cart/cartSlice';
// import Action from "@/components/Buttons/Action";
// import AddToCartBtn from "@/components/Buttons/AddToCartBtn";



export default function Product() {
  const dispatch = useDispatch();
  const [loading, setLoading] = useState(false);


    const settings = {
        dots: true,
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


      
  const [flashsaleproduct, setflashsaleproduct] = useState([]);
  const flashsaleproductFunction = async () => {
    try {
        const response = await fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/flash-sale`);
        const data = await response.json();
        setflashsaleproduct(data);
    } catch (error) {
    }
  }
  useEffect(() =>{
    flashsaleproductFunction();
  },[]);

  return (
    <div className='content'>
      <Slider {...settings}>
        {flashsaleproduct?.items?.map((chunckdata, index) => (
          <div key={index}>
    
          <div className="row m-0">
            {chunckdata ? (
                <>
                 {chunckdata.map((data, i) => (
                    
                    <div key={i} className="col-12 px-0">
                        
                        {/* <div className="product-box">
                            <div className="product-image">
                                <a href="product-left-thumbnail.html">
                                    <Image src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/${data.default_image}`}
                                        className="img-fluid blur-up lazyload"  width={180} height={140} alt=""/>
                                </a>
                                <Action data={data}></Action>
                            </div>
                            <div className="product-detail">
                                <a href="product-left-thumbnail.html">
                                    <h6 className="name">{ data.title }</h6>
                                </a>

                                <h5 className="sold text-content">
                                    <span className="theme-color price">BD { data.price_after_offer }</span>
                                    {data.price_after_offer < data.price ? (
                                    <del>BDT { data.price}</del>
                                    ):('')}
                                </h5>
                                <div className="add-to-cart-box mt-2">
                                    {data?.in_stock > 0 && data?.qty > 0 ? (
                                        <> 
                                        {data?.product_type == 'variable' ? (
                                            <Link href={'/product/'+`${data?.slug}`}><button className="btn btn-add-cart addcart-button" tabIndex="0">Details </button></Link>
                                        ):(
                                            <AddToCartBtn  data={data}></AddToCartBtn>
                                        )}
                                        </>
                                    ):(
                                        <button className="btn btn-sm mb-2 out_of_stock"> Out Of Stock </button>
                                    )}
                                </div>
                            </div>
                        </div> */}
                      <SingleProduct data={data}> </SingleProduct>

                    </div>
                ))}
                </>
            ):('')}
          </div>
          </div>
        ))}
      </Slider>

    </div>
  )
}