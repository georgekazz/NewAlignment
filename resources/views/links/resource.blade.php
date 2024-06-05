<a href="{{$resource}}"
   data-toggle="tooltip"
   data-placement="auto"
   data-container="body"
   data-animations="true"
   title="{{$resource}}"
   >
   {{ \App\Models\RDFTrait::label($graph, $resource)?:EasyRdf\RdfNamespace::shorten($resource, true)}}
</a>