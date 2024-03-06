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
import AddToCartBtn from "@/components/Buttons/AddToCartBtn";


const Compare = () => {
    const { data:userdata, status, update, session }= useSession();


    const [comparesdata, setCompares] = useState(null);
    const load_compare = async () => {
        let session_key = localStorage.getItem("session_key");

        try {
            const token = localStorage.getItem('token');
            if(token){
                const response = await fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/initCompare?session_key=`+session_key,{
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                });
                const data = await response.json();
            
                setCompares(data);
                update({ 
                    compares_data: response.data.total,
                });
            }
        } catch (error) {
            console.log('User order catch error');
        }
    }
    


    const removeCompare = async (product_id) => {
        const token = localStorage.getItem('token');
        let session_key = localStorage.getItem("session_key");
        let formData = new FormData();
        formData.append('product_id', product_id);
        formData.append('session_key', session_key);

        try {
            const response = await axios.post(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/remove-compare`, formData, {
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
                load_compare();
                update({ 
                    compares_data: response.data.total,
                });
                toast.success(response.data.message, {
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
        load_compare();
    }, []);
    return (
        <FrontendLayouts>
            <Meta title="about page" keywords="dhroobo keywords" decription="dhroobo decription" />
            
            <div className="container wishlistpage">
                <div className="tab-pane fade show" id="pills-wishlist" role="tabpanel" aria-labelledby="pills-wishlist-tab">
                    <div className="dashboard-wishlist">
                        <div className="title">
                            <h2> Compare Products</h2>
                            <span className="title-leaf title-leaf-gray">
                                <svg className="icon-width bg-gray">
                                    {/* <use xlink:href="https://themes.pixelstrap.com/fastkart/assets/svg/leaf.svg#leaf"></use> */}
                                </svg>
                            </span>
                        </div>
                        <div className="row g-sm-4 g-3"> 
                        {comparesdata?.total > 0 ? (
                        <div className="col-md-12 compare_wrapper table-responsive">
                            <table className="table table-bordered" width="100%">
                                <thead>
                                <tr>
                                    <th width="15%">Product</th>
                                    <th width="10%"> Price</th>
                                    <th width="10%">Product Type</th>
                                    
                                    <th width="15%">Specification</th>
                                    
                                    <th width="10%">Custom Options</th>
                                    <th width="20%">Product Description</th>
                                    <th width="15%">Action</th>
                                </tr>
                                </thead>
                                <tbody className="compare_table">
                                    {comparesdata?.compares?.map((data, index) => ( 
                                    <tr key={index}>
                                        <td>
                                        <Link href={`/product/${data?.product.slug}`}>
                                            <div className="compare_image">
                                                <Image src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/${data?.product.default_image}`} alt="" width={120} height={100} />
                                            </div>
                                            
                                            <p>{data?.product.title}</p>
                                        </Link>
                                        </td>
                                        <td><span>BDT { data.product.price_after_offer }</span></td>
                                        <td>{ data.product.product_type }</td>
                                    
                                        <td> 
                                            {data.specification?.map((sp, index) => ( 
                                                <span key={index}>
                                                    {sp.meta_value? (
                                                        <p> 
                                                            <strong className="text-capitalize">{ sp.meta_key}</strong> : {sp.meta_value} 
                                                        </p> 
                                                    ):('')}
                                                </span>
                                            ))}
                                        </td>
                                        
                                        <td>
                                            {data.meta?.map((metas, index) => ( 
                                            <span key={index}> 
                                                {metas.meta_key == 'custom_options'? (
                                                    <span>
                                                    {metas.decoded_custom_options?.map((options, index) => ( 
                                                        <span key={index}> 
                                                            <ul>
                                                                <li>
                                                                    <b>{options.title} : </b> <br/> 
                                                                    {options.value?.map((val, index) => ( 
                                                                    <span key={val}> 
                                                                        <i className="fa fa-arrow-circle-o-right" aria-hidden="true"></i>  { val.title } <br/>
                                                                    </span>
                                                                    ))}
                                                                </li>
                                                            </ul>
                                                        </span>
                                                    ))}
                                                    </span>
                                                ):('')}
                                            </span>
                                            ))}
                                        </td>
                                        <td>{ data.product.short_description.substr(0, 150)+".." } </td>

                                        <td className='compareActionButton'>
                                            {data.product.in_stock > 0 && data.product.qty > 0 ? (
                                            <span>
                                                {data.product.product_type == 'variable' ? (
                                                <span>
                                                    <Link  href={`/product/${data?.product.slug}`}> 
                                                    <button className="btn btn-primary btn-sm mb-2"> Details</button> 
                                                    </Link>
                                                </span>
                                                ):(
                                                <span className="compare_spinner">
                                                    {/* <button  className={`btn btn-primary btn-sm mb-2 disabledbtn${data?.product.slug}`} onClick={() => addTocart(data?.product?.id)}> <i className="fa fa-shopping-basket"></i> Add To Cart</button> */}
                                                    <AddToCartBtn data={data}></AddToCartBtn>
                                                </span>
                                                )}
                                            </span>
                                            ):(
                                                <span>
                                                    <button  className="btn btn-sm mb-2 out_of_stock text-center"> Out Of Stock </button>
                                                    <br/>
                                                    <button className={`mt-2 btn btn-primary btn-sm disabledbtn${data?.product.slug}`} onClick={() => restockRequest(data?.product?.id, data.product.seller_id)} > <i className="fa fa-bicycle mr-2"></i> Restock Request </button>
                                                </span>
                                            )}
                                            <br/>
                                            <button className="btn btn-danger btn-sm compare_spinner mt-1"  onClick={() => removeCompare(data?.product?.id)}> <i className="fa fa-trash" aria-hidden="true"></i> Remove</button>
                                        </td>
                                    </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                        ):(
                        <div className="col-md-12 compare_not_found_wrapper">
                            <div className="row promotion_page">
                                <div className="col-md-12 text-center">
                                    <h3>No product found in your compare list.</h3>
                                </div>
                            </div>
                        </div>
                        )}

                        </div>
                    </div>
                </div>
            </div>
        </FrontendLayouts>
    )
}

// export default Wishlist
export default withAuth(Compare);

