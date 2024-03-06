import FrontendLayouts from '@/components/Layouts/Frontend/FrontendLayouts'
import Image from 'next/image';
import { useSelector, useDispatch } from 'react-redux';
import { AddToCart, InitCart, removeItem, updateQty, selectTotalQuantity } from '../lib/features/cart/cartSlice';
import Link from 'next/link';
import { useEffect, useState } from 'react';
import RemoveCartBtn from "@/components/Buttons/RemoveCartBtn";


const CartPage = () => {
  const [loading, setLoading] = useState(false);
  const cart = useSelector((state) => state.cart);
  const dispatch = useDispatch();
  const totalQuantity = useSelector(selectTotalQuantity);
  





  const handleupdateQty = async (rowId, update) => {
    setLoading(true);
    const data = {rowId:rowId, update:update}
    try {
      await dispatch(updateQty(data));
      await dispatch(InitCart());
      setLoading(false);
    } catch (error) {
      console.error('Failed to add item to cart', error);
      setLoading(false);
    }
  }
  

  return (
    <FrontendLayouts> 
    <div className="container">
      {totalQuantity < 1 ? (
        <div className='empty_cart'><h4>Your cart is empty !</h4></div>
        
      ) : (
        <>



          <section id="cart-page">
              <div className="container">
                <div className="row cart-page-container">
                  <div className="col-12 col-sm-12 col-md-8 col-lg-9 pr-0">
                    <div className="row shoping-cart-text">
                      <div className="col-12 col-sm-12 col-md-12">
                        <h4>Shopping Cart</h4>
                      </div>
                    </div>
                    <div className="cart-calculation">
                      <table  className="table text-left cart_table" width="100%">
                        <thead>
                          <tr>
                            <th width="5%"> Image</th>
                            <th width="30%">Product Name</th>
                            <th width="20%"> Price</th>
                            <th width="15%"> Quantity</th>
                            <th width="20%"> Total</th>
                            <th width="5%" className="text-right;"> Remove</th>
                          </tr>
                        </thead>
                        
                        {cart?.initcart?.cart?.map((cartgroup, index) => (
                        <tbody  key={index}>
                          
                          <tr className="group_header mb-3">
                            <td colSpan="7">
                              <small>Shipped by :  <b> <Link className="text-dark" href={`/shop/${cartgroup.shop_info.shop_slug}`}>{cartgroup.shop_info.shop_name}</Link></b></small>
                            </td>
                          </tr> 


                          {cartgroup.items?.map((cart, index) => (
                          <tr key={index} className="cart_product_group">
                          
                            <td> <div className="product-cart-img"> <Image src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/${cart.product.default_image}`} alt="" width={120} height={100} /> </div> </td>
                            
                             <td> 
                              <div className="table-item"> 
                                <Link href={`/product/${cart.product.slug}`}> {cart.product.title} </Link>
                              
                                {cart.product.product_type == 'variable' ? (
                                  <span>
                                    {cart.variable_options?.map((vOption, key) => (
                                    <p key={key} className="mb-0 text-capitalize font-13"> <b>{key}</b> : {vOption}</p>
                                    ))}
                                  </span>
                                ):('')}


                                {cart.product.product_type == 'digital' && cart.variable_options ? (
                                <span>
                                  <p className="mb-0 text-capitalize font-13"> <b>Contact Number</b> : {cart.variable_options}</p>
                                </span>
                                ):('')}




                              </div> 
                            </td>
                            <td> 
                              <div className="table-item">BDT { cart.price }</div>

                              <div className="old-price"> 
                              {Number(cart.price_before_offer) >  Number(cart.price) ? (
                              <del>BDT { cart.price_before_offer }</del> 
                              ):('')}
                              </div>
                            
                            </td>
                            
                            {cart.product.product_type == 'simple'  || cart.product.product_type == 'variable' ? (
                            <td> 
                              <div className="table-item">
                                <div className="full-quantity">
                                  {/* <button onClick={() => handleupdateQty(cart.row_id, -1)} className="cart_minus_btn"> <div className="crt cart-minus"> - </div> </button> 
                                  <div className="crt cart-qty"> <input  id={`Product${index}`} type="text" data-catqty={cart.qty} className="cart-qty-input" readOnly value={cart.qty} data-rowid={cart.row_id} data-productid={cart.product_id} data-userid={cart.user_id} /> </div>
                                  <button onClick={() => handleupdateQty(cart.row_id, 1)} className="cart_minus_btn"><div className="crt cart-plus" > + </div></button> 
                                   */}
                                  
                                  <div className="qty_buttons">
                                    <div className="input-group">
                                      <span onClick={() => handleupdateQty(cart.row_id, -1)} className="input-group-btn">
                                          <button type="button" className="quantity-left-minus btn btn-danger btn-number btn-sm">
                                            <i className="fa fa-minus" aria-hidden="true"></i>
                                          </button>
                                      </span>
                                      <input type="text" id="quantitypage" name="quantity" className="form-control input-number" value={cart?.qty} readOnly />
                                      <span onClick={() => handleupdateQty(cart.row_id, 1)} className="input-group-btn">
                                          <button type="button" className="quantity-right-plus btn btn-success btn-number btn-sm">
                                            <i className="fa fa-plus" aria-hidden="true"></i>
                                          </button>
                                      </span>
                                  </div>
                                </div>



                                
                                </div>
                              </div>
                            </td>
                            ):(
                            <td style="text-align: center;"> - </td>
                            )}

                            
                            <td> 
                              <div className="table-item">BDT { Number(cart.price) * Number(cart.qty) } </div>
                            </td>
                            
                            <td className="text-right;"> 
                              <RemoveCartBtn  data={cart.row_id}></RemoveCartBtn>

                            </td> 


                          </tr>
                          ))} 
                        </tbody>
                        ))}
    
                        
                      </table>
                    </div>
                  </div>
                  <div className="col-12 col-sm-12 col-md-4 col-lg-3 payment">
                    <div className="note note_cart_page">
                      <h4>Order Summary</h4>
                      <hr/>
                    </div>
                    <div className="payment-calculation">
                      <ul>
                        
                        <li> <b>Total Item(s) : </b>  <span> { cart?.initcart?.total_items  }</span></li>
                        <li> <b>Sub Total : </b>  <span>BDT { cart?.initcart?.sub_total  }</span></li>
                        
                      </ul>
                    </div>
                    <div className="procced-checkout">
                      <ul>
                        <li> <Link href="checkout"><button className="btn btn-primary site_color1" >Proceed To Checkout</button> </Link> </li>
                      </ul>    
                    </div>
                  </div>
                </div>
              </div>
            </section>



        </>
      )}
    </div>
    </FrontendLayouts>
  )
}


export default CartPage;
