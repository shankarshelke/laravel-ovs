{{-- <div class="page-heading">
    <h3>{{$module_title}}</h3>
    <ul class="breadcrumb">
        @if(isset($module_title) && !empty($module_title))
        <li>
            @if(isset($sub_module_title) && !empty($sub_module_title))
            <a href="{{$module_url or 'javascript:void(0)'}}">
              {{$module_title}}
            </a>
            @else
            {{$module_title or ''}}
            @endif
        </li>
        @endif

        @if(isset($sub_module_title) &&  !empty($sub_module_title))
        <li class="active">
            {{$sub_module_title or ''}}
        </li>
        @endif

    </ul>
</div> --}}

<div class="row">
    <div class="col-md-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb panel" style="margin-bottom: 15px;">
<!--            {{-- <li><a href="{{ $admin_url_path or '' }}"><i class="fa fa-home"></i> Dashboard</a></li> --}}-->
            <li>
                <a href="{{ $parent_module_url or 'javascript:void(0)' }}">{{ $parent_module_title or '' }}</a>
            </li>
            <li class="{{ $module_url or 'javascript:void(0)' }}">
                @if(isset($module_url_path) && $module_url_path != '' && isset($sub_module_title) && $sub_module_title != '' )
                <a href="{{ $module_url_path or 'javascript:void(0)' }}">
                    {{$module_title}}
                </a>
                @else
                {{$module_title}}
                @endif
            </li>
            @if(isset($sub_module_title) && $sub_module_title != '' )
            <li class="'javascript:void(0)">
                {{ $sub_module_title or ''}}
            </li>
            @endif
        </ul>
        <!--breadcrumbs end -->
    </div>
</div>