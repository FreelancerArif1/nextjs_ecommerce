import FrontendLayouts from '@/components/Layouts/Frontend/FrontendLayouts'
import Head from 'next/head'
import Image from 'next/image'
import Meta from "@/components/Meta";
import { useEffect, useState } from 'react';
import Input from '@/components/Input';
import { useRouter } from 'next/router';
import axios from 'axios';
import Link from 'next/link';
import withAuth from '@/components/Layouts/Frontend/withAuth';
import { getSession, signIn, signOut, useSession,  } from 'next-auth/react';
import { ToastContainer, toast } from 'react-toastify';

const Wishlist = () => {
    const { data:userdata, status, update, session }= useSession();


    const [wishlists, setWishlist] = useState(null);
    const load_wishlists = async () => {
        try {
            const token = localStorage.getItem('token');
            if(token){
                const response = await fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/initwishlist`,{
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                });
                const data = await response.json();
                setWishlist(data);
            }
        } catch (error) {
            console.log('User order catch error');
        }
    }
    


    const RemoveWishlist = async (wishlist_id) => {
        const token = localStorage.getItem('token');

        try {
            const response = await axios.get(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/remove-wishlist/`+wishlist_id,{
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
            if (response.data.status == 0) {
                toast.error(response.data.message, {
                    position: "bottom-right",
                    autoClose: 2000,
                    hideProgressBar: false,
                    closeOnClick: true,
                    pauseOnHover: true,
                    draggable: false,
                    progress: undefined,
                    theme: "light",
                });
            }else if(response.data.status == 1){
                update({ 
                    wishlist_data: response.data.wishlist,
                });
                load_wishlists();
                toast.error(response.data.message, {
                    position: "bottom-right",
                    autoClose: 2000,
                    hideProgressBar: false,
                    closeOnClick: true,
                    pauseOnHover: true,
                    draggable: false,
                    progress: undefined,
                    theme: "light",
                });
            }
        } catch (error) {
            console.log('error ', error);
        }
    }



    useEffect(() => {
        if (status == 'authenticated') {
            load_wishlists();
        }
    }, []);
    return (
        <FrontendLayouts>
            <Meta title="about page" keywords="dhroobo keywords" decription="dhroobo decription" />
            
            <div className="container wishlistpage">
                <div className="tab-pane fade show" id="pills-wishlist" role="tabpanel" aria-labelledby="pills-wishlist-tab">
                    <div className="dashboard-wishlist">
                        <div className="title">
                            <h2> Wishlist</h2>
                            <span className="title-leaf title-leaf-gray">
                                <svg className="icon-width bg-gray">
                                    {/* <use xlink:href="https://themes.pixelstrap.com/fastkart/assets/svg/leaf.svg#leaf"></use> */}
                                </svg>
                            </span>
                        </div>
                        <div className="row g-sm-4 g-3">





                        {wishlists?.wishlist?.map((wishlist, i) => (
                            <div key={i} className="col-xxl-2 col-lg-3 col-md-3 col-sm-6">
                                <div className="product-box-3 theme-bg-white h-100">
                                    <div className="product-header">
                                        <div className="product-image">
                                            <Link href={'/product/'+`${wishlist?.product?.slug}`}>
                                                <Image src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/${wishlist?.product?.default_image}`}
                                                    className="img-fluid blur-up lazyload" width={400} height={400} alt="" />
                                            </Link>

                                            <div className="product-header-top">
                                                <button onClick={() => RemoveWishlist(wishlist?.id)} className="btn wishlist-button close_button">
                                                    <i data-feather="x">x</i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div className="product-footer">
                                        <div className="product-detail">
                                            <Link href={'/product/'+`${wishlist?.product?.slug}`}>
                                                <h5 className="name">{wishlist?.product?.title}</h5>
                                            </Link>
                                            
                                            <h5 className="price mt-2">
                                                <span className="theme-color">BDT {wishlist?.price}</span>
                                                
                                                {wishlist?.price_before_offer > wishlist?.price ? (
                                                    <del> BDT {wishlist?.price_before_offer}</del>
                                                ):('')}
                                                
                                            </h5>
                                            <div className="add-to-cart-box mt-2">
                                                {wishlist?.product?.in_stock > 0 && wishlist?.product?.qty > 0 ? (
                                                    <> 
                                                    {wishlist?.product?.product_type == 'variable' ? (
                                                        <Link href={'/product/'+`${wishlist?.product?.slug}`}><button className="btn btn-add-cart addcart-button" tabindex="0">Details </button></Link>
                                                    ):(
                                                        <button className="btn btn-add-cart addcart-button" tabindex="0">Add </button>
                                                    )}
                                                    </>
                                                ):(
                                                    <button className="btn btn-sm mb-2 out_of_stock"> Out Of Stock </button>
                                                )}
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ))}

                            

                        </div>
                    </div>
                </div>
            </div>
        </FrontendLayouts>
    )
}

// export default Wishlist
export default withAuth(Wishlist);

