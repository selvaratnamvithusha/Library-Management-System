<div class="currently-market">
  <div class="container">
      <div class="row">
          <div class="col-lg-6">
              <div class="section-heading">
                  <div class="line-dec"></div>
                  <h2>Items Currently In The Market.</h2>
              </div>
          </div>

          <div class="col-lg-6">
              <div class="filters">
                  
              </div>
          </div>

          <div class="col-lg-12">
              <div class="row grid">

                  @foreach($data as $book)
                  <div class="col-lg-6 currently-market-item all msc">
                      <div class="item">
                          <div class="left-image">
                              <!-- Updated image path using asset() -->
                              <img class="img_book" src="{{ asset('storage/' . $book->book_img) }}" style="border-radius: 20px; min-width: 195px;">
                              
                          </div>
                          <div class="right-content">
                              <h4>{{ $book->title }}</h4>
                              <span class="author">
                                  <!-- Updated author image path using asset() -->
                                  <img class="img_author" src="{{ asset('storage/' . $book->author_img) }}" style="max-width: 50px; border-radius: 50%;">
                                  
                                  <h6>{{ $book->author_name }}</h6>
                              </span>
                              <div class="line-dec"></div>
                              <span class="bid">
                                  Current Available<br><strong>{{ $book->quantity }}</strong><br>
                              </span>

                              <div class="text-button">
                                  <a href="{{ url('book_details', $book->id) }}">View Book Details</a>
                              </div>

                            </br>

                              {{-- <div class="">
                                  <a class="btn btn-primary" href="{{ url('borrow_books', $book->id) }}">Apply to Borrow</a>
                              </div> --}}

                              <div>
                                <a class="btn btn-info" href="{{ url('borrow_books', $book->id) }}">Apply to Borrow</a>
                              </div>
                              
                          </div>
                      </div>
                  </div>
                  @endforeach

              </div>
          </div>
      </div>
  </div>
</div>

