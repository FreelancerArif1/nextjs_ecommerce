<div class="row">
	<div class="col-md-6 grid-margin">
		<div class="card">
			<div style="min-height: 530px" class="card-body">
				<p class="content_title">Personal Information</p>

				<div class="row">

					<div class="col-md-6">
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">Full Name</label>
							<div class="col-sm-8">{{ $vendor->name }}</div>
						</div>
					</div>


					<div class="col-md-6">
						<div class="form-group row">
							<label class="col-sm-4 col-form-label"> Email</label>
							<div class="col-sm-8">
							{{ $vendor->email }}
							</div>
						</div>
					</div>

					<div class="form-group col-md-6">
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">Phone</label>
							<div class="col-sm-8">
							{{ $vendor->phone }}
							</div>
						</div>
					</div>


					<div class="col-md-6"></div>

					<div class="col-md-6 form-group ">
						<div class="row">
							<label class="col-sm-4 col-form-label">NID Front Side</label>
							<div class="col-sm-8">
								<p class="selected_images_gallery">
									<span>
									<img src="/{{ $vendor->nid_front_side}}"> 
									</span>
								</p>
							</div>
						</div>
					</div>

					<div class="col-md-6 form-group ">
						<div class="row">
							<label class="col-sm-4 col-form-label">NID Back Side</label>
							<div class="col-sm-8">
								<p class="selected_images_gallery">
									<span>
									<img src="/{{$vendor->nid_back_side}}"> 
									</span>
								</p>
							</div>
						</div>
					</div>



					<div class="form-group col-md-6">
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">Profile Image</label>
							<div class="col-sm-8">
								<p class="selected_images_gallery">
									<span>
									<img src="/{{ $vendor->avatar}}"> 
									</span>
								</p>
							</div>
						</div>
					</div>


					<div class="col-md-6">
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">Status</label>
							<div class="col-sm-8">
								<div class="form-check form-check-flat">
									<label class="switch">{{ $account_status}}</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="col-md-6 grid-margin">
		<div class="card">
			<div class="card-body">
				<p class="content_title">Shop Information</p>
				<div class="row">

					<div class="col-md-6">
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">Shop Name </label>
							<div class="col-sm-8">
								{{$vendor->shopinfo->name}}
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">Shop Url</label>
							<div class="col-sm-8">
								{{$vendor->shopinfo->slug }}
							</div>
						</div>
					</div>


					<div class="col-md-6">
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">Shop Phone </label>
							<div class="col-sm-8">
								{{ $vendor->shopinfo->phone }}
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">Shop Email</label>
							<div class="col-sm-8">
								{{$vendor->shopinfo->email }}
							</div>
						</div>
					</div>


					<div class="form-group col-md-6">
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">Shop Logo </label>
							<div class="col-sm-8">
								<p class="selected_images_gallery">
									<span>
										<img src="/{{ $vendor->shopinfo->logo}}">
									</span>
								</p>
							</div>
						</div>
					</div>

					<div class="form-group col-md-6">
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">Shop Banner</label>
							<div class="col-sm-8">
								<p class="selected_images_gallery">
									<span>
										<img src="/{{ $vendor->shopinfo->banner}}"> 
									</span>
								</p>
							</div>
						</div>
					</div>

					<div class="col-md-6 form-group">
						<div class="row">
							<label class="col-sm-4 col-form-label">Trade License</label>
							<div class="col-sm-8">
								<p class="selected_images_gallery">
									<span>
										<img src="/{{ $vendor->shopinfo->trade_license }}"> 
									</span>
								</p>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">Division</label>
							<div class="col-sm-8">
								{{ $division }}
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">District</label>
							<div class="col-sm-8">
								{{ $district }}
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">Upazila</label>
							<div class="col-sm-8">
								{{ $upazila }}
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">Union</label>
							<div class="col-sm-8">
								{{ $union }}
							</div>
						</div>
					</div>


					<div class="col-md-6">
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">Address</label>
							<div class="col-sm-8">
								{{ $vendor->shopinfo->address }}
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">Shop Status</label>
							<label class="col-md-6 col-form-label">{{ $seller_status }}
							</label>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>


	<div class="col-md-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<p class="content_title">Bank Account Details</p>
				<div class="row">

					<div class="col-md-6">
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">Bank Name </label>
							<div class="col-sm-8">
								{{ $vendor->shopinfo->bank_name }}
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">Account Name </label>
							<div class="col-sm-8">
								{{ $vendor->shopinfo->account_name }}
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">Account Number </label>
							<div class="col-sm-8">
								{{ $vendor->shopinfo->account_number }}
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">Routing Number</label>
							<div class="col-sm-8">
								{{ $vendor->shopinfo->routing_number }}
							</div>
						</div>
					</div>
				</div>

				<p class="content_title">Mobile Financial Service Accounts</p>
				<div class="row">

					<div class="col-md-6">
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">Bkash Number</label>
							<div class="col-sm-8">
								{{ $vendor->shopinfo->bkash }}
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">Rocket Number</label>
							<div class="col-sm-8">
								{{ $vendor->shopinfo->rocket }}
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">Nagad Number</label>
							<div class="col-sm-8">
								{{ $vendor->shopinfo->nagad }}
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">Upay Number</label>
							<div class="col-sm-8">
								{{ $vendor->shopinfo->upay }}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	@if(count($request) > 0)
		<div class="col-md-12 grid-margin">
			<div class="card">
				<div class="card-body">
					<p class="content_title">Payment history</p>
					<div class="row">
						<div class="col-lg-12">
							<table id="listTable" class="table">
			                    <thead>
			                        <tr>
			                            <th>S.N</th>
			                            <th>Date</th>
			                            <th>Seller</th>
			                            <th>Payment Method</th>
			                            <th>Requested Amount</th>
			                            <th>Paid Amount</th>
			                            <th>Message</th>
			                            <th>Status</th>
			                        </tr>
			                    </thead>
			                    <tbody>
			                        @foreach ($request as $row)
			                            <tr>
			                                <td>{{ $row->id }}</td>
			                                <td>{{ $row->date }}</td>
			                                <td>{{ $row->seller->name }}</td>
			                                <td>
			                                	{{ $row->payment_method }}
			                                </td>
			                                <td>
			                                	{{ Helper::getDefaultCurrency()->currency_symbol . ' ' . $row->requested_amount }}
			                                </td>
			                                <td class="text-success">{{ Helper::getDefaultCurrency()->currency_symbol . ' ' . $row->amount_to_pay }}</td>
			                                <td>{{ $row->message }}</td>
			                                <td>
			                                    <span class="badge text-light p-1"
			                                        style="background-color: {{ $row->statuses->color_code }};">{{ $row->statuses->title }}</span>
			                                </td>
			                            </tr>
			                        @endforeach
			                    </tbody>
			                </table>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endif
</div>