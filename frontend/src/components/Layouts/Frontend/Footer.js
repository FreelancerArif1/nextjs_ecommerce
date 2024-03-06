import Head from 'next/head'
import Image from 'next/image'
import Link from 'next/link';

const Footer = ({information}) => {
    return (
        <div className='footer'>
            <footer className="pt-3">
                <div className="container-fluid-lg">
                
                    <div className="main-footer pb-1">
                        <div className="row g-md-4 g-3 pt-3">
                            <div className="col-xl-4 col-lg-4 col-sm-6">
                                <div className="footer-logo">
                                    <div className="theme-logo">
                                    <Link href="/" className="web-logo nav-logo" >
                                        <Image src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/${information.header_logo}`} className="img-fluid blur-up lazyload" width={100}  height={100}  alt="" />
                                    </Link>
                                    </div>

                                    <div className="footer-logo-contain">
                                        <p>{ information.cnf_appdesc }</p>
                                    </div>
                                </div>
                            </div>

                            <div className="col-xl-2 col-lg-2 col-md-2 col-sm-6">
                                <div className="footer-title">
                                    <h4>Quick Links</h4>
                                </div>

                                <div className="footer-contain">
                                    <ul>
                                        <li><Link href="/pages/about" className="text-content"> About Us </Link> </li>
                                        <li><Link href="/career" className="text-content"> Career </Link> </li>
                                        <li><Link href="/blog" className="text-content"> Blog </Link> </li>
                                        <li><Link href="/contact" className="text-content"> Help Center </Link> </li>
                                        {/* <li><Link href="vendor" className="text-content">  Join As Seller </Link></li> */}
                                        {/* <li><Link href="{name: 'corporate'}" className="text-content">  Join As Corporate User </Link></li> */}
                                    </ul>
                                </div>
                            </div>

                            <div className="col-xl-2 col-sm-6">
                                <div className="footer-title">
                                    <h4>Policies</h4>
                                </div>
                                <div className="footer-contain">
                                    <ul>
                                        {information.privacy_policy ? (<li>  <Link  className="text-content" href={`/pages/${information.privacy_policy}`}> Privacy Policy </Link> </li>):('')}
                                        {information.return_policy ? (<li>  <Link  className="text-content" href={`/pages/${information.return_policy}`}> Return & Refund Policy  </Link> </li>):('')}
                                        {information.warranty_policy ? (<li>  <Link  className="text-content" href={`/pages/${information.warranty_policy}`}> Warranty Policy  </Link> </li>):('')}
                                        {information.terms_of_use ? (<li> <Link className="text-content" href={`/pages/${information.terms_of_use}`}>  Terms and Conditions </Link> </li>):('')}
                                        {information.cancel_policy ? (<li>  <Link className="text-content" href={`/pages/${information.cancel_policy}`}> Cancellation Policy </Link> </li>):('')}
                                        {/* <li> <b>TIN Number:</b> {information.cnf_tin_number} </li> */}
                                    </ul>
                                </div>
                            </div>

                            <div className="col-xl-4 col-lg-4 col-sm-12">
                                <div className="footer-title">
                                    <h4>Contact Us</h4>
                                </div>

                                <div className="footer-contact">
                                    <ul>

                                    <li> <span>{ information.cnf_address }</span> </li>
                                    <li> <b>E-mail: </b> <a href="'mailto:'+information.cnf_email">{ information.cnf_email }</a> </li>
                                    <li> <b> Mobile: </b> 
                                        {information.cnf_phone1 ? (<a href="'tel:'+information.cnf_phone1">{ information.cnf_phone1 }</a> ):('')}
                                        {information.cnf_phone2 ? (<a href="'tel:'+information.cnf_phone2">,{ information.cnf_phone2 }</a> ):('')}
                                    </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="sub-footer pt-2 pb-2">
                        <div className="reserve"> 
                            <h6 className="text-content">©2024 Dhroobo All rights reserved.</h6>
                        </div>

                        <div className="paymentfooter">
                            <Image src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/${information.accepted_payment_image}`} className="img-fluid blur-up lazyload" width={300}  height={35}  alt="" />
                        </div>

                        <div className="social-link">
                            <h6 className="text-content">Stay connected :</h6>
                            <ul>
                                {information.facebook ? (<li> <a className="text-content facebook_a" href={information.facebook} target="__blank"> <i className="fa fa-facebook-official" aria-hidden="true"></i>  </a> </li>):('')}
                                {information.twitter ? (<li> <a className="text-content twiter_a" href={information.twitter} target="__blank"> <i className="fa fa-twitter" aria-hidden="true"></i> </a> </li>):('')}
                                {information.instagram ? (<li> <a className="text-content instagram_a" href={information.instagram} target="__blank"> <i className="fa fa-instagram" aria-hidden="true"></i> </a> </li>):('')}
                                {information.youtube ? (<li> <a className="text-content youtube_a" href={information.youtube} target="__blank"> <i className="fa fa-youtube-play" aria-hidden="true"></i> </a> </li>):('')}
                                {information.pinterest ? (<li> <a className="text-content pinterest_a" href={information.pinterest} target="__blank"><i className="fa fa-pinterest" aria-hidden="true"></i></a> </li>):('')}
                                    
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    )
}

export default Footer