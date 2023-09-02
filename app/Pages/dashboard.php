<?php

return <<<temp
    <div class="container mt-3">
        <div class="row">
            <div class="col">
                <h5 class="text-capitalize fw-light">Overview Dashboard</h5>
            </div>
            <div class="col d-flex justify-content-end">
                <select class="form-select form-select-sm rounded-0"  style="width:auto;outline:none;" aria-label=".form-select-sm example">
                    <option selected>Today</option>
                    <option value="1">This Week</option>
                    <option value="2">This Mounth</option>
                    <option value="3">This Year</option>
                </select>
            </div>
        </div>
        <hr />
        <div
        class="
        row
        justify-content-center
        row-cols-1 row-cols-sm-1 row-cols-md-3 row-cols-lg-3 row-cols-xl-4
        mt-3
        "
    >

        <div class="col mt-1">
            <div class="card rounded-0 p-1 border-0 bg-white shadow">
                <div class="card-body">
                    <h6 class="card-title text-start text-capitalize fw-normal text-black-50">
                        Total Sells
                    </h6>
                    <p class="card-text" style="color:#14213d">25.50$</p>
                </div>
            </div>
        </div>

        <div class="col mt-1">
            <div class="card rounded-0 p-1 border-0 bg-white shadow">
                <div class="card-body">
                    <h6 class="card-title text-start text-capitalize fw-normal text-black-50">
                    Total Online Store Visitors</h6>
                    <p class="card-text" style="color:#14213d">408</p>
                </div>
            </div>
        </div>

        <div class="col mt-1">
            <div class="card rounded-0 p-1 border-0 bg-white shadow">
                <div class="card-body">
                    <h6 class="card-title text-start text-capitalize fw-normal text-black-50">
                        repeart customer rate
                    </h6>
                    <p class="card-text" style="color:#14213d">20.2%</p>
                </div>
            </div>
        </div>

        <div class="col mt-1">
            <div class="card rounded-0 p-1 border-0 bg-white shadow">
                <div class="card-body">
                    <h6 class="card-title text-start text-capitalize fw-normal text-black-50">
                        Toatal Orders
                    </h6>
                    <p class="card-text" style="color:#14213d">201</p>
                </div>
            </div>
        </div>

        <div class="col mt-1">
            <div class="card rounded-0 p-1 border-0 bg-white shadow">
                <div class="card-body">
                    <h6 class="card-title text-start text-capitalize fw-normal text-black-50">
                        online store conversion rate
                    </h6>
                    <p class="card-text" style="color:#14213d">25%</p>
                </div>
            </div>
        </div>

    </div>
    </div>
    temp;
