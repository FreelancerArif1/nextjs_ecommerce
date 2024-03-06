import FrontendLayouts from '@/components/Layouts/Frontend/FrontendLayouts'
import Head from 'next/head'
import Image from 'next/image'
import Meta from "@/components/Meta";
import Link from 'next/link';
const Cancel = () => {
    function handleClick() {
        alert('kkkkkk');
    }

    return (
        <FrontendLayouts>
            <Meta title="Cancel | Dhroobo" keywords="dhroobo keywords" decription="dhroobo decription" />
            <section className="breadscrumb-section pt-0">
                <div className="container-fluid-lg">
                    <div className="row">
                        <div className="col-12">
                            <div className="breadscrumb-contain breadscrumb-order">
                                <div className="order-box">
                                    <div className="order-image">
                                        <div className="checkmark">
                                            <svg className="crossmark addClass" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"><circle className="crossmark__circle addClass" cx="26" cy="26" r="25" fill="none"/>
                                                <path className="cross__path cross__path--right addClass" fill="none" d="M16,16 l20,20" />
                                                <path className="cross__path cross__path--left addClass" fill="none" d="M16,36 l20,-20" />
                                            </svg>
                                        </div>
                                    </div>

                                    <div className="order-contain">
                                        <h3 className="Failed">Cancel</h3>
                                        <p> We received your purchase request</p>
                                        <Link href="/my_account" className="btn theme-bg-color  btn-sm mb-3 mt-3 mr-2"> Go to my account </Link> 
                                        <Link  href="/products"  className="btn btn-dark btn-sm">  Continue shopping  </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </FrontendLayouts>
    )
}

export default Cancel
