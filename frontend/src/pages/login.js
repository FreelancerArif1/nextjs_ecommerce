
"use client"
import FrontendLayouts from '@/components/Layouts/Frontend/FrontendLayouts'
import Head from 'next/head'
import Image from 'next/image'
import Meta from "@/components/Meta";
import axios from 'axios';
import Input from '@/components/Input'
import InputError from '@/components/InputError'
import { useState } from 'react'
import Link from 'next/link';
import { useSession, signIn, signOut } from "next-auth/react"


import { useRouter } from 'next/router';
const Login = () => {
    const router = useRouter();

    const [loginData, setloginData] = useState({
        phone: '',
        password: '',
    });
    
    const handleChange = (e) => {
        const { name, value } = e.target;
        setloginData((prevData) => ({ ...prevData, [name]: value }));
    }
    
    const loginSubmit = async (e) => {
        e.preventDefault();

        
        // const response = await axios.post(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/login`, loginData);
        // if (response.data.status == 0) {
        //     response.data.message.phone ? $('.phone_error').text(response.data.message.phone) : $('.phone_error').text('');
        //     response.data.message.password ? $('.password_error').text(response.data.message.password) : $('.password_error').text('');
        // }else if(response.data.status == 2){
        //     $('.other_error').text(response.data.message);
        // }else{
        //     $('.errormessage').text('');
        //     localStorage.setItem("token", response.data.token);
        //     //router.push('/my_account');
        //     const result = await signIn('credentials', {
        //         phone,
        //         password,
        //         redirect: false,
        //       });
          
        //       if (result.error) {
        //         console.error('Login failed:', result.error);
        //       } else {
        //         //router.push('/my_account');
        //       }
        // }
        const phone = loginData.phone;
        const password = loginData.password;
        const result = await signIn('credentials', {
            phone,
            password,
            redirect: false,
        });
      
        if(result.error) {
            $('.other_error').text('Error ! Invalid credentials.');
          } else {
            router.push('/my_account');
        }





    }


    

return (
    <FrontendLayouts>
    <section className="log-in-section background-image-2 section-b-space">
    <div className="container-fluid-lg w-100">
        <div className="row">
            <div className="col-xxl-6 col-xl-5 col-lg-6 d-lg-block d-none ms-auto">
                <div className="image-contain">
                    <Image src="/assets/images/inner-page/log-in.png" className="Image-fluid" width={550} height={465} alt="image" />
                </div>
            </div>

            <div className="col-xxl-4 col-xl-5 col-lg-6 col-sm-8 mx-auto">
                <div className="log-in-box">
                    <div className="log-in-title">
                        <h4>Log In Your Account</h4>
                    </div>

                    <div className="input-box">
                        <form className="row g-4" onSubmit={loginSubmit}>
                            <div className="col-12">
                                <div className="form-floating theme-form-floating log-in-form">
                                    <Input type="text" name="phone" value={loginData.phone} onChange={handleChange} className="form-control" id="phone" placeholder="Phone/Email" />
                                    <label htmlFor="phone">Phone/Email</label>
                                </div>
                                <small className='errormessage phone_error'></small>
                            </div>

                            <div className="col-12">
                                <div className="form-floating theme-form-floating log-in-form">
                                    <Input name="password" value={loginData.password} onChange={handleChange} type="password" className="form-control" id="password"
                                        placeholder="Password" />
                                    <label htmlFor="password">Password</label>
                                </div>
                                <small className='errormessage password_error'></small>
                            </div>

                            <div className="col-12">
                                <div className="forgot-box">
                                    <div className="form-check ps-0 m-0 remember-box">
                                        <Input className="checkbox_animated check-box" type="checkbox"
                                            id="flexCheckDefault" />
                                        <label className="form-check-label" htmlFor="flexCheckDefault">Remember me</label>
                                    </div>
                                    <a href="forgot.html" className="forgot-password">Forgot Password?</a>
                                </div>
                            </div>

                            <div className="col-12">
                                <small className='errormessage other_error'></small>
                                <button className="btn btn-animation w-100 justify-content-center" type="submit">Log In</button>
                            </div>
                        </form>
                    </div>
            

                    <div className="sign-up-box">
                        <h4>Don't have an account?</h4>
                        <Link href="register">Sign Up</Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
    </FrontendLayouts>
    )
}

export default Login