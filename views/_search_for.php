<form autocomplete="off" id="frm_search" action="/search-results" method="GET" class="relative flex items-center ml-auto ">
    <input type="hidden" name="source" value="<?=$tag?>">
    <input name="query" type="text" 
        class="pl-7  w-full" 
        placeholder="Search"
        oninput="search_users('<?=$tag?>')"
        onfocus="document.querySelector('#query_results').classList.remove('hidden')"
        onblur="document.querySelector('#query_results').classList.add('hidden')"
        >
    <button class="absolute flex items-center">
        <span class="material-symbols-outlined ml-1 font-thin">
            search
        </span>            
    </button>
    <div id="query_results" 
    class="absolute hidden overflow-x-hidden overflow-scroll w-full h-48 bg-bkg-1 top-full left-0 border border-slate-500">
    
    </div>
</form>