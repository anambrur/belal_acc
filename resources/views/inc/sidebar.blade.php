<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="{{ Route::currentRouteName() == 'dashboard.index' ? 'active' : '' }}">
                    <a href="{{ route('dashboard.index') }}"><i class="fa-solid fa-gauge"></i><span>
                            Dashboard</span>
                    </a>
                </li>
                <li class="submenu">
                    <a class="{{ Route::current()->getPrefix() === '/product' ? 'active' : '' }}"
                        href="javascript:void(0);"><i class="fa-solid fa-box"></i><span>
                            Product</span>
                        <span class="menu-arrow"></span></a>
                    <ul @if (Route::current()->getPrefix() === '/product') style="display:block!important;" @endif>
                        <li><a class="{{ Route::currentRouteName() === 'product.index' ? 'active' : '' }}"
                                href="{{ route('product.index') }}">Product List</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'product.create' ? 'active' : '' }}"
                                href="{{ route('product.create') }}">Add Product</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'category.index' ? 'active' : '' }}"
                                href="{{ route('category.index') }}">Category List</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'category.create' ? 'active' : '' }}"
                                href="{{ route('category.create') }}">Add Category</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'unit.index' ? 'active' : '' }}"
                                href="{{ route('unit.index') }}">Unit List</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'unit.create' ? 'active' : '' }}"
                                href="{{ route('unit.create') }}">Add Unit</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'company.index' ? 'active' : '' }}"
                                href="{{ route('company.index') }}">Company List</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'company.create' ? 'active' : '' }}"
                                href="{{ route('company.create') }}">Add Company</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'company.create' ? 'active' : '' }}"
                                href="{{ route('batch.index') }}">Batch List</a></li>

                    </ul>
                </li>


                <li class="submenu">
                    <a class="{{ Route::current()->getPrefix() === '/sell' ? 'active' : '' }}"
                        href="javascript:void(0);"><i class="fa-solid fa-cart-shopping"></i><span>
                            Sales</span>
                        <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a class="{{ Route::currentRouteName() === 'sell.index' ? 'active' : '' }}"
                                href="{{ route('sell.index') }}">Sales List</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'sell.create' ? 'active' : '' }}"
                                href="{{ route('sell.create') }}">Sales Create</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'sellsReturn.index' ? 'active' : '' }}"
                                href="{{ route('sellsReturn.index') }}">Sales Return List</a></li>
                        <li>
                            <a class="{{ Route::currentRouteName() === 'sellsReturn.create' ? 'active' : '' }}"
                                href="{{ route('sellsReturn.create') }}">New Sales Return</a>
                        </li>
                    </ul>
                </li>



                <li class="submenu">
                    <a class="{{ Route::current()->getPrefix() === '/purchase' ? 'active' : '' }}"
                        href="javascript:void(0);"><i class="fa-solid fa-truck"></i><span>
                            Purchase</span>
                        <span class="menu-arrow"></span></a>
                    <ul @if (Route::current()->getPrefix() === '/purchase') style="display:block!important;" @endif>
                        <li><a class="{{ Route::currentRouteName() === 'purchase.index' ? 'active' : '' }}"
                                href="{{ route('purchase.index') }}">Purchase List</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'purchase.create' ? 'active' : '' }}"
                                href="{{ route('purchase.create') }}">Add Purchase</a></li>
                    </ul>
                </li>



                <li class="submenu">
                    <a class="{{ Route::current()->getPrefix() === '/expense' ? 'active' : '' }}"
                        href="javascript:void(0);"><i class="fa-solid fa-receipt"></i><span>
                            Expense</span>
                        <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a class="{{ Route::currentRouteName() === 'expense.index' ? 'active' : '' }}"
                                href="{{ route('expense.index') }}">Expense List</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'expense.create' ? 'active' : '' }}"
                                href="{{ route('expense.create') }}">Add Expense</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'expenseCategory.index' ? 'active' : '' }}"
                                href="{{ route('expenseCategory.index') }}">Expense Category List</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'expenseCategory.create' ? 'active' : '' }}"
                                href="{{ route('expenseCategory.create') }}">Expense Category Add</a></li>
                    </ul>
                </li>


                {{-- <li><a class="{{ Route::currentRouteName() === 'paymentMethod.index' ? 'active' : '' }}"
                    href="{{ route('paymentMethod.index') }}">Payment Settings</a></li> --}}
                <li class="submenu">
                    <a class="{{ Route::current()->getPrefix() === '/accounts' ? 'active' : '' }}"
                        href="javascript:void(0);"><i class="fa-solid fa-building-columns"></i><span>
                            Acccounting</span>
                        <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a class="{{ Route::currentRouteName() === 'accounts.index' ? 'active' : '' }}"
                                href="{{ route('accounts.index') }}">Accounts</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'accounts.create' ? 'active' : '' }}"
                                href="{{ route('accounts.create') }}">Add Accounts</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'balanceAdjustmentView' ? 'active' : '' }}"
                                href="{{ route('balanceAdjustmentView') }}">Balance Adjustment</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'balanceTransferView' ? 'active' : '' }}"
                                href="{{ route('balanceTransferView') }}">Balance Transfer</a></li>
                    </ul>
                </li>








                <li class="submenu">
                    <a class="{{ Route::current()->getPrefix() === '/people' ? 'active' : '' }}"
                        href="javascript:void(0);"><i class="fa-solid fa-users"></i><span>
                            People</span>
                        <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a class="{{ Route::currentRouteName() === 'customer.index' ? 'active' : '' }}"
                                href="{{ route('customer.index') }}">Customer List</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'customer.create' ? 'active' : '' }}"
                                href="{{ route('customer.create') }}">Add Customer </a></li>
                        <li><a class="{{ Route::currentRouteName() == 'supplier.index' ? 'active' : '' }}"
                                href="{{ route('supplier.index') }}">Supplier List</a></li>
                        <li><a class="{{ Route::currentRouteName() == 'supplier.create' ? 'active' : '' }}"
                                href="{{ route('supplier.create') }}">Add Supplier </a></li>
                        
                    </ul>
                </li>




                <li class="submenu">
                    <a class="{{ Route::current()->getPrefix() === '/profit' ? 'active' : '' }}"
                        href="javascript:void(0);"><i class="fa-solid fa-chart-column"></i><span>
                            Reports</span>
                        <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a class="{{ Route::currentRouteName() === 'profitCalculation' ? 'active' : '' }}"
                                href="{{ route('profitCalculation') }}">Daily Report</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'dateFilter' ? 'active' : '' }} d-none"
                                href="{{ route('profitCalculation') }}">Profit report</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'profitLossList' ? 'active' : '' }}"
                                href="{{ route('profitLossList') }}">Profit &amp; Loss</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'sellsReportList' ? 'active' : '' }}"
                                href="{{ route('sellsReportList') }}">Sales Report</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'purchaseReportList' ? 'active' : '' }}"
                                href="{{ route('purchaseReportList') }}">Purchases Report</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'customerDueList' ? 'active' : '' }}"
                                href="{{ route('customerDueList') }}">Customer Due Report</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'supplierDueList' ? 'active' : '' }}"
                                href="{{ route('supplierDueList') }}">Supplier Due Report</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'collectionList' ? 'active' : '' }}"
                                href="{{ route('collectionList') }}">Collections Report</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'expenseReportList' ? 'active' : '' }}"
                                href="{{ route('expenseReportList') }}">Expense Report</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'balance_sheet_view' ? 'active' : '' }}"
                                href="{{ route('balance_sheet_view') }}">Balance Sheet</a></li>
                    </ul>
                </li>



                <li class="submenu">
                    <a class="{{ Route::current()->getPrefix() === '/loan' ? 'active' : '' }}"
                        href="javascript:void(0);"><i class="fa-solid fa-handshake"></i><span>
                            Loans</span>
                        <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a class="{{ Route::currentRouteName() === 'loan.index' ? 'active' : '' }}"
                                href="{{ route('loan.index') }}">Loan List</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'loan.create' ? 'active' : '' }}"
                                href="{{ route('loan.create') }}">Loan Add</a></li>

                    </ul>
                </li>


                <li class="submenu">
                    <a href="javascript:void(0);"><i class="fa-solid fa-user-gear"></i><span>
                            Users</span>
                        <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a class="{{ Route::currentRouteName() === 'user.create' ? 'active' : '' }}"
                                href="{{ route('user.create') }}">New User </a></li>
                        <li><a class="{{ Route::currentRouteName() === 'user.index' ? 'active' : '' }}"
                                href="{{ route('user.index') }}">Users List</a></li>
                    </ul>
                </li>



                <li class="submenu">
                    <a class="{{ Route::current()->getPrefix() === '/payment' ? 'active' : '' }}"
                        href="javascript:void(0);"><i class="fa-solid fa-gear"></i><span>
                            Settings</span>
                        <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a class="{{ Route::currentRouteName() === 'store.edit' ? 'active' : '' }}"
                                href="{{ route('store.edit') }}">Store Settings</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'location.index' ? 'active' : '' }}"
                                href="{{ route('location.index') }}">Location Settings</a></li>
                        <li><a class="{{ Route::currentRouteName() === 'road.index' ? 'active' : '' }}"
                                href="{{ route('road.index') }}">Road Settion</a></li>
                        {{-- <li><a href="taxrates.html">Tax Rates</a></li> --}}
                    </ul>
                </li>




            </ul>
        </div>
    </div>
</div>
