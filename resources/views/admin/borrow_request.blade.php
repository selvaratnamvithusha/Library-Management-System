<!DOCTYPE html>
<html>
  <head> 
    @include('admin.css')

    <style type="text/css">
    .center
    {
        text-align: center;
        margin: auto;
        width: 80%;
        border: 1px solid white;
        margin-top: 60px;
    }

    th
    {
        background-color: skyblue;
        text-align: center;
        color: white;
        font-size: 15px;
        font-weight: bold;
        padding: 10px;
    }

    td img {
            width: 60px;
            height: 60px;
            border-radius: 5px;
        }
        .btn {
            margin: 2px;
        }
        .status-approved {
            color: skyblue;
            font-weight: bold;
        }
        .status-rejected {
            color: red;
            font-weight: bold;
        }
        .status-returned {
            color: yellow;
            font-weight: bold;
        }
        .status-applied {
            color: white;
            font-weight: bold;
        }
        .no-requests {
            text-align: center;
            color: gray;
            font-size: 18px;
            margin-top: 20px;
        }

    </style>
  </head>
  <body>
    @include('admin.header')
    <div class="d-flex align-items-stretch">
      <!-- Sidebar Navigation-->
      @include('admin.sidebar')
      <!-- Sidebar Navigation end-->

      <div class="page-content">
        <div class="page-header">
          <div class="container-fluid">

            <table class="center">
                <tr>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Book title</th>
                    <th>Quantity</th>
                    <th> Borrow Status</th>
                    <th>Book Image</th>
                    <th>Actions</th>
                </tr>

                @foreach ($book as $book)
                    
                
                <tr>
                    <td>{{$book->user->name}}</td>
                    <td>{{$book->user->email}}</td>
                    <td>{{$book->user->phone}}</td>
                    <td>{{$book->book->title}}</td>
                    <td>{{$book->book->quantity}}</td>
                    <td>
                        @if($book->status=='approved')
                        <span style="color: skyblue;">{{$book->status}}</span>
                        @endif

                        @if($book->status=='rejected')
                        <span style="color: red;">{{$book->status}}</span>
                        @endif

                        @if($book->status=='returned')
                        <span style="color: yellow;">{{$book->status}}</span>
                        @endif

                        @if($book->status=='Applied')
                        <span style="color: white;">{{$book->status}}</span>
                        @endif
                    </td>
                    <td>
                        
                        <img class="book_img" src="{{ asset('storage/' . $book->book->book_img) }}" alt="Book Image" style="width: 60px; height: 60px;">
                        
                    </td>

                    <td>
                        <a class="btn btn-warning" href="{{url('approve_book', $book->id)}}">Approved</a>
                        <a class="btn btn-danger" href="{{url('rejected_book', $book->id)}}">Rejected</a>
                        <a class="btn btn-info" href="{{url('return_book', $book->id)}}">Returned</a>
                    </td>
                </tr>
                @endforeach

            </table>









          </div>
        </div>
      </div>
    
      @include('admin.footer')
  </body>
</html> 



