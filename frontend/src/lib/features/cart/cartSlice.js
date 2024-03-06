// store/cartSlice.js
import { createSlice, createAsyncThunk } from '@reduxjs/toolkit';
import axios from 'axios';
import Link from 'next/link';
import { ToastContainer, toast } from 'react-toastify';

export const selectTotalQuantity = state => state.cart?.initcart?.total_items;



export const AddToCart = createAsyncThunk(
  'cart/AddToCart',
  async (product, { rejectWithValue }) => {
    const token = localStorage.getItem('token');
    let session_key = localStorage.getItem("session_key");
    let formData = new FormData();
    formData.append('product_id', product.id); 
    formData.append('session_key', session_key); 
    formData.append('qty', 1); 
    try {
      const response = await axios.post(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/add-to-cart`, formData,{
          headers: {
              Authorization: `Bearer ${token}`,
          },
      });
      if(response.data.status == 1){
        toast.success('Successfully added to cart !', {
          position: "bottom-right",
          autoClose: 2000,
          hideProgressBar: false,
          closeOnClick: true,
          pauseOnHover: true,
          draggable: false,
          progress: undefined,
          theme: "light",
        });
        return product.id;
      }else{
        toast.error(response.data.message, {
          position: "bottom-right",
          autoClose: 3000,
          hideProgressBar: false,
          closeOnClick: true,
          pauseOnHover: true,
          draggable: false,
          progress: undefined,
          theme: "light",
        });
      }


    } catch (error) {
      toast.error('Somethign went wrong.', {
        position: "bottom-right",
        autoClose: 4000,
        hideProgressBar: false,
        closeOnClick: true,
        pauseOnHover: true,
        draggable: false,
        progress: undefined,
        theme: "light",
      });
    }
  }
);


export const updateQty = createAsyncThunk(
  'cart/updateQty',
  async (data, { rejectWithValue }) => {
    const token = localStorage.getItem('token');
    const session_key = localStorage.getItem('session_key');
    
    let formData = new FormData();
    formData.append('rowId', data.rowId); 
    formData.append('update', data.update); 
    formData.append('session_key', session_key); 

    console.log('data=', data);
    if(data){ 
      try {
        const response = await axios.post(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/update-qty`, formData,{
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        //console.log('removeItem=', response);
      } catch (error) {
        return rejectWithValue(error.response);
      }
    }
  }
)

export const removeItem = createAsyncThunk(
  'cart/removeItem',
  async (row_id, { rejectWithValue }) => {
    const token = localStorage.getItem('token');
    let formData = new FormData();
    formData.append('row_id', row_id); 
    formData.append('session_key', localStorage.getItem('session_key')); 

    if(row_id){ 
      try {
        const response = await axios.post(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/remove-cart-item`, formData,{
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        //console.log('removeItem=', response);
      } catch (error) {
        return rejectWithValue(error.response);
      }
    }
  }
)



export const InitCart = createAsyncThunk(
  'cart/fetchCartData',
  async () => {
    const token = localStorage.getItem('token');
    let session_key = localStorage.getItem("session_key");

    try {
      const response = await axios.get(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/initcart?session_key=`+session_key,{
        headers: {
            Authorization: `Bearer ${token}`,
        },
      });
      return response.data;

    } catch (error) {
      return rejectWithValue(error.response.data);
    }
  }
)
const initialState = {
  items: [],
  status: 'idle',
  error: null,
  initcart:null,
};

const cartSlice = createSlice({
  name: 'cart',
  initialState,
  reducers: {

    // simpleAddToCart (state, action) {
    //   const product = action.payload;
    //   const existingItem = state.items.find(item => item.id === product.id);
    //   if (!existingItem) {
    //     state.items.push({ ...product, qty: 1 });
    //   } else {
    //     existingItem.qty++;
    //   }
    // },
    // removeItem(state, action) {
    //   const idToRemove = action.payload;
    //   state.items = state.items.filter(item => item.id !== idToRemove);
    // },
    // updateQuantity(state, action) {
    //   const { id, qty } = action.payload;
    //   const itemToUpdate = state.items.find(item => item.id === id);
    //   if (itemToUpdate) {
    //     itemToUpdate.qty = qty;
    //   }
    // },
    // clearCart(state) {
    //   state.items = [];
    // },

  },
  extraReducers: (builder) => {
      builder
      .addCase(InitCart.fulfilled, (state, action) => {
        state.initcart = (action.payload); 
      })
      .addCase(AddToCart.fulfilled, (state, action) => {
        state.items.push(action.payload); 
      })
      .addCase(removeItem.fulfilled, (state, action) => {
        state.items.push(action.payload); 
      })
      .addCase(updateQty.fulfilled, (state, action) => {
        state.items.push(action.payload); 
      })
  },



});

export const { simpleAddToCart, updateQuantity, clearCart } = cartSlice.actions;

// Export the reducer directly
export const cartReducer = cartSlice.reducer;
