

import { configureStore } from '@reduxjs/toolkit';
import { cartReducer, InitCart } from './features/cart/cartSlice';

const store = configureStore({
  reducer: {
    cart: cartReducer,
  },
});



export default store;

store.dispatch(InitCart());
