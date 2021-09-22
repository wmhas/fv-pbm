<!DOCTYPE html>
<html>
<body>
    
<style>
   
    .div1 
    {
        position:relative; 
        left:185px; 
        top:245px;
        
        line-height:25px;
        font-weight: bold;
        font-family: Arial, Helvetica, sans-serif;
        text-transform: uppercase; 
    }

    .div2 
    {
        position:relative; 
        left:95px; 
        top:260px;
        
        line-height:25px;
        font-weight: bold;
        font-family: Arial, Helvetica, sans-serif;
        text-transform: uppercase; 
        font-size: 9;
    }
    

    .div3 
    {
        position:relative; 
        left:95px; 
        top:275px;
        
        line-height:22px;
        font-weight: bold;
        font-family: Arial, Helvetica, sans-serif;
        text-transform: uppercase; 
        font-size: 10;
    }
    
    .floatright{

    }
    .floatleft{

    }
</style>
   
            <div class="div1">
                <div>{{ $patient->identification }}</div>
            <br>
                <div>{{ $patient->address_1 }}</div>
            
                <div>{{ $patient->address_2 }}</div>
                
                <div>{{ $patient->postcode }}</div>
            
                <div>{{ $patient->city }}</div>
            <br>
                <div>{{ $patient->phone }}</div>
            
                <div> {{ $patient->email }}</div>
            </div>
        

    <p style="page-break-before: always">
    

        @if ($patient->relation == 'CardOwner')
                <div class="div2">   
                    <div>
                        @if (!empty($patient->card)) {{ $patient->card->salutation }}@else @endif
                        @if (!empty($patient->card)) {{ $patient->card->name }}@else @endif
                    </div>
                
                    <div>
                        @if (!empty($patient->card)) {{ $patient->card->ic_no }}@else @endif
                    </div>
                        
                    <div>
                        @if (!empty($patient->card)) {{ $patient->card->army_pension }}@else @endif
                    </div>
                
                    {{-- <div style="float:right">
                        @if (!empty($patient->card->type == 'Pensionable Veteran'))
                                /
                        @else
                                /
                        @endif
                    </div> --}}
                    
                    {{-- <div>
                        @if (!empty($patient->card)) {{ $patient->card->army_type }}@else @endif
                    </div> --}}
                </div>
            
        @else
                <div class="div2">
                     <div>
                        @if (!empty($patient->card)) {{ $patient->card->salutation }}@else @endif
                        @if (!empty($patient->card)) {{ $patient->card->name }}@else @endif
                    </div>
                <br>
                    <div>
                        @if (!empty($patient->card)) {{ $patient->card->ic_no }}@else @endif
                    </div>
                        
                    <div>
                        @if (!empty($patient->card)) {{ $patient->card->army_pension }}@else @endif
                    </div>
                
                    {{-- <div>
                        @if (!empty($patient->card->type == 'Pensionable Veteran'))
                        /
                    @else
                        /
                    @endif
                    </div> --}}
                    
                    {{-- <div>
                        @if (!empty($patient->card)) {{ $patient->card->army_type }}@else @endif
                    </div> --}}
                </div>

           <br><br><br><br>

                    <div class="div3">
                        <div>{{ $patient->salutation }}
                             {{ $patient->full_name }}
                        </div>
                <br>
                        <div>{{ $patient->identification }}</div>
                    
                        {{-- <div>{{ $patient->relation }}
                        </div> --}}
                    </div>
        @endif
        
</body>
   
   