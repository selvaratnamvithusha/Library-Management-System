<!DOCTYPE html>
<html lang="en">

<head>
  <base href="/public">

  @include('home.css')

  <style>
    .item-details-page {
      margin: 50px auto;
      background-color: #1f1f1f; /* Dark background for black theme */
      padding: 20px;
      border-radius: 10px;
      color: #ffffff; /* White text for readability */
    }

    .details-container {
      display: flex;
      gap: 20px; /* Space between image and details */
      align-items: flex-start; /* Align image and text to the top */
    }

    .img_book {
      border-radius: 10px;
      width: 200px; /* Set a fixed width */
      height: 280px; /* Set a fixed height */
      object-fit: cover; /* Ensure images maintain aspect ratio */
    }

    .img_author {
      width: 60px; /* Set author image width */
      height: 60px; /* Set author image height */
      border-radius: 50%;
      object-fit: cover; /* Prevent distortion */
      margin-right: 10px;
    }

    .author {
      display: flex;
      align-items: center;
      gap: 10px; /* Space between author image and name */
    }

    h4 {
      margin-top: 10px;
      font-size: 24px;
      font-weight: bold;
      color: #ffffff;
      text-transform: uppercase; /* Keep all book names in uppercase */
    }

    p {
      font-size: 14px;
      color: #cccccc; /* Slightly lighter text for description */
      margin-top: 10px;
    }

    .row span {
      display: block;
      text-align: center;
      color: #ffffff;
      font-size: 16px;
      font-weight: bold;
    }

    .row span strong {
      display: block;
      color: #ffc107; /* Golden color for emphasis */
    }

    .details-container > div {
  flex: 1;
  max-width: 500px;
}

  </style>
</head>

<body>

  @include('home.header')

  <div class="item-details-page">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="section-heading">
            <div class="line-dec"></div>
            <h2>View Details <em>For Item</em> Here.</h2>
          </div>
        </div>

        <div class="col-lg-12">
          <div class="details-container">
            <!-- Book Image -->
            <div>
              <img class="img_book" src="{{ asset('storage/' . $book->book_img) }}" alt="Book Image">
            </div>

            <!-- Book Details -->
            <div>
              <h4>{{ $book->title }}</h4>
              <div class="author">
                <img class="img_author" src="{{ asset('storage/' . $book->author_img) }}" alt="Author Image">
                <h6>{{ $book->author_name }}</h6>
              </div>
              <p>{{ $book->description }}</p>
              <div class="row">
                <div class="col-3">
                  <span class="bid">
                    Available<br><strong>{{ $book->quantity }}</strong><br>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @include('home.footer')

</body>
</html>
