<!DOCTYPE html>
<html>
<head>
  @include('admin.css')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <style type="text/css">
    .center-table {
      margin: auto;
      width: 80%;
      margin-top: 20px;
      
    }

    th {
      background-color: skyblue;
      padding: 10px;
      border: 1px solid white;

    }

    td {
      padding: 10px;
      text-align: center;
    }

    .modal-title {
      font-size: 20px;
      font-weight: bold;
    }
  </style>
</head>
<body>
@include('admin.header')

<div class="d-flex align-items-stretch">
  <!-- Sidebar Navigation -->
  @include('admin.sidebar')
  <!-- Sidebar Navigation end -->

  <div class="page-content">
    <div class="page-header">
      <div class="container-fluid">

        <!-- Category Table -->
        <div class="center-table">
          <h1 style="color:white; text-align:center;">Manage Categories</h1>
          <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addCategoryModal">Add Category</button>

          <table class="table table-bordered">
            <thead>
            <tr>
              <th>Category Name</th>
              <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($categories as $item)
              <tr>
                <td>{{$item->cat_title}}</td>
                <td>
                  <button class="btn btn-warning" data-toggle="modal" data-target="#editCategoryModal{{$item->id}}">
                    Edit
                  </button>
                  

                  <!-- Delete Button -->
                  <form action="{{ route('category.destroy', $item->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this category?');">Delete</button>
                </form>


                </td>
              </tr>

              <!-- Edit Category Modal -->
              <div class="modal fade" id="editCategoryModal{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel{{$item->id}}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Edit Category</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      @if ($errors->any())
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

                      <form action="{{url('update_category', $item->id)}}" method="post">
                        @csrf
                        <div class="form-group">
                          <label>Category Name</label>
                          <input type="text" name="cat_name" class="form-control" value="{{$item->cat_title}}" required>
                        </div>
                        <button type="submit" class="btn btn-info">Update</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach

            <script>
              document.getElementById('editCategoryForm{{$item->id}}').addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent form submission
            
                const categoryInput = document.getElementById('editCategoryName{{$item->id}}');
                const categoryError = document.getElementById('editCategoryError{{$item->id}}');
            
                // Clear previous errors
                categoryError.textContent = '';
            
                if (categoryInput.value.trim() === '') {
                  categoryError.textContent = 'Category name is required.';
                } else if (categoryInput.value.length > 255) {
                  categoryError.textContent = 'Category name cannot exceed 255 characters.';
                } else {
                  this.submit(); // Submit the form if validation passes
                }
              });
            </script>

            
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @if ($errors->any())
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

        <form action="{{url('add_category')}}" method="post">
          @csrf
          <div class="form-group">
            <label>Category Name</label>
            <input type="text" name="category" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary">Add Category</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  document.getElementById('addCategoryForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent form submission

    const categoryInput = document.getElementById('categoryName');
    const categoryError = document.getElementById('categoryError');

    // Clear previous errors
    categoryError.textContent = '';

    if (categoryInput.value.trim() === '') {
      categoryError.textContent = 'Category name is required.';
    } else if (categoryInput.value.length > 255) {
      categoryError.textContent = 'Category name cannot exceed 255 characters.';
    } else {
      this.submit(); // Submit the form if validation passes
    }
  });
</script>

@include('admin.footer')

</body>
</html>
