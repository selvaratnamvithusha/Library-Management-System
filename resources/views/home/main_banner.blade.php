<style>
.recommended-books {
  padding: 20px 0;
}
.book-card {
  background: #fff;
  padding: 10px;
  border-radius: 8px;
  box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
}
.book-card img {
  width: 100%;
  height: 150px;
  object-fit: cover;
  border-radius: 5px;
}
.book-card h6 {
  font-size: 14px;
  margin-top: 5px;
}
.book-card a {
  margin-top: 10px;
}

</style>
<!-- ***** Main Banner Area Start ***** -->         
<div class="main-banner">
    <div class="container">
      <div class="row">

        @if(session()->has('message'))

          <div class="alert alert-success">

          {{session()->get('message')}}
          <button type="button" class="close" data-bs-dismiss="alert" aria-hidden="true">X</button>
            
          </div>
          @endif

          
        <div class="col-lg-6 align-self-center">
          <div class="header-text">
            <h6>Book is Knowledge</h6>
            <h2>Knowledge is Power</h2>
            <p>Library is a really cool and professional design for your websites. This HTML CSS template is based on Bootstrap v5 and it is designed for related web portals.</p>
            <div class="buttons">
              <div class="border-button">
                <a href="{{url('explore')}}">Explore Top Books</a>
                <a href="{{url('recommend')}}">Recommend Books For You </a>
              </div>
              
            </div>

    
          </div>
        </div>
        <div class="col-lg-5 offset-lg-1">
          <div class="">
            <div class="item">
              <img src="assets/images/banner.png" alt="">
            </div>
            <div class="item">
              <img src="assets/images/banner2.png" alt="">
            </div>
          </div>
        </div>
      </div>

      
    </div>
  </div>
  <!-- ***** Main Banner Area End ***** -->
  