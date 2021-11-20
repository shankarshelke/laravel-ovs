<div class="page-title-breadcurm-section">
    <div class="container">
    <div class="page-head-title-section">
        {{$sub_module_title or ''}}
    </div>
    <div class="page-breadcurm-section">
        <a href="{{$common_url or 'javascript:void(0)'}}">Home</a> &nbsp; &gt; &nbsp; 
        @if(isset($module_title) &&  !empty($module_title))
          <a href="{{$module_url or 'javascript:void(0)'}}">{{$module_title or ''}}</a> &nbsp; &gt; &nbsp;
        @endif
        @if(isset($sub_module_title) &&  !empty($sub_module_title))
            <span>{{$sub_module_title or ''}}</span>
        @endif
    </div>
    <div class="clearfix"></div>
</div>
</div>