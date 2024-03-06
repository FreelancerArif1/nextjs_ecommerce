
import Head from 'next/head';
import Script from 'next/script';
import { randomBytes } from 'crypto';
import axios from 'axios';


const corsOptions ={
  origin:'https://dhroobo-frontend.dhakaitsolutions.com',
  credentials:true,            //access-control-allow-credentials:true
  optionSuccessStatus:200
}


import React, { useEffect, useState } from 'react';

import $ from "jquery"
import 'bootstrap/dist/css/bootstrap.css';
import * as feather from 'feather-icons/dist/feather.min';
import Header from '@/components/Layouts/Frontend/Header'
import Footer from '@/components/Layouts/Frontend/Footer'
import { SessionProvider } from "next-auth/react"
import { Provider } from 'react-redux';       // Importing Provider
import store from '../lib/store';   




export default function App({
  Component,
    pageProps: { session, ...pageProps },
  }) {

    const [information, setBook] = useState([]);
    const fetchBook = async () => {
    try {
        const response = await fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/information?upazila_id=`+localStorage.getItem('upazila_id'));
        const data = await response.json();
        setBook(data);
    } catch (error) {

    }
  }
  const [categories, setcategories] = useState([]);
  const loadCategories = async () => {
    try {
        const response = await fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/category-list`);
        const data = await response.json();
        setcategories(data);
    } catch (error) {
  }
}

const [popularCategories, setpopularCategories] = useState([]);
const loadpopularCategories = async () => {
  try {
      const response =await axios.get(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/get-popular-categories`);
      setpopularCategories(response.data.data);
  } catch (error) {
}
}


const [all_slider, setall_slider] = useState([]);
const load_Slider = async () => {
  try {
      const response =await axios.get(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/slide-show`);
      setall_slider(response.data);
  } catch (error) {
}
}



const [ad1, setad1] = useState([]);
const [ad2, setad2] = useState([]);
const [slider1, setslider1] = useState([]);
const [slider2, setslider2] = useState([]);

const loadadd = async () => {
  try {
      const response =await axios.get(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/get-add`);
      setad1(response.data.ad1);
      setad2(response.data.ad2);
      setslider1(response.data.slider1);
      setslider2(response.data.slider2);
  }catch (error) {

  }
}

  const [allAddress, setAddress] = useState(null);
  const getAllAddress = async () => {
      try {
          const token = localStorage.getItem('token');
          if(token){
              const response = await fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/alladdress`,{
                  headers: {
                      Authorization: `Bearer ${token}`,
                  },
              });
              const data = await response.json();
              setAddress(data);
          }
      } catch (error) {
          console.log('User order catch error');
      }
  }  


function generateSessionKey(length = 32) {
  const bytes = randomBytes(Math.ceil(length / 2));
  return bytes.toString('hex').slice(0, length);
}


  useEffect(() =>{
    loadCategories();
    loadpopularCategories();
    loadadd();
    load_Slider();
    const token = localStorage.getItem('token');
    const session_key = localStorage.getItem('session_key');
    if(!session_key){
      localStorage.setItem('session_key', Number( new Date().getTime())+generateSessionKey());
    }
    fetchBook();
    if(token && !allAddress){
      getAllAddress();
    }
  },[]);

  return (
    <Provider store={store}>
    <SessionProvider session={session}>
      <Header information={information} categories={categories} />
      <Component information={information} ad1={ad1} ad2={ad2} popularCategories={popularCategories} all_slider={all_slider}  allAddress={allAddress} {...pageProps} />
      <Footer information={information} />
    
    </SessionProvider>
    </Provider>
  )
}