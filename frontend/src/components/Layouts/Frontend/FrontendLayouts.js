import Head from 'next/head'
import Searchbar from '@/components/Layouts/Frontend/Searchbar'
import Navbar from '@/components/Layouts/Frontend/Navbar'
import Header from '@/components/Layouts/Frontend/Header'
import Footer from '@/components/Layouts/Frontend/Footer'
import * as feather from 'feather-icons/dist/feather.min';
import React, { useEffect, useState } from 'react';

const FrontendLayouts = ({ children }) => {
  useEffect(() => {
    import("bootstrap/dist/js/bootstrap");
  }, []);
  useEffect(() => {
    feather.replace();
  }, []);
  useEffect(() => {
    typeof document !== undefined ? require('bootstrap/dist/js/bootstrap') : null
  }, [])

    return (
        <div>
                {children}
        </div>
    )
}
export default FrontendLayouts
