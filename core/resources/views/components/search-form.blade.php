@props(['placeholder'=>'Search...'])
<form action="" method="GET" class="form-inline">
    <div class="input-group">
        <input type="text" name="search" class="form-control bg--white" placeholder="{{ __($placeholder) }}" value="{{ request()->search }}">
        <button class="btn btn--primary" type="submit"><i class="la la-search"></i></button>
    </div>
</form>