@extends('layouts.app')

@section('content')
<style type="text/css">
    .bs-acc {
        margin: 20px;
    }
</style>
<div class="container" style="padding-bottom: 250px;">
    <h2 style="width:100%; border-bottom:4px solid #C8B273; color:#C8B273;">
    <b>User Guide</b>
</h2>
    <div class="bs-acc">
        <div class="panel-group" id="accordion">
            <div class="panel panel-default">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
 style="color:#000; text-decoration:none;">
                    <div class="panel-heading" style="background-color: #eee;">
                        <h3 class="panel-title" style="font-size:28px;">
                          <b>How to use this application?</b>
                        </h3>
                    </div>
                </a>
                <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <ol style="font-size:17px; line-height:2;">
    <li>Make sure you have registered/signed up first</li>
    <li>Select the product you want to buy</li>
    <li>Complete the checkout process for your order</li>
</ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
