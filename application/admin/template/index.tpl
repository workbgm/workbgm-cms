{extend name="./data/tpl/base.html" /}
{block name="body"}
<div class="container-fluid">
    <div class="container-fluid">
        <ul class="nav nav-tabs">
            <li class="active"><a>[TABLECOMMENT]管理</a></li>
            <li><a href="{:url('[MODULE]/[TABLENAME]/add')}">添加[TABLECOMMENT]</a></li>
        </ul>
        <div class="container-fluid">
                <div class="container-fluid">
                    {include file="[TABLENAME]/form" /}
                    <div class="table-responsive">  <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                [TH]
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            {volist name="list" id="vo"}
                                <tr>
                                    [TD]
                                    <td>
                                        <a href="{:url('[MODULE]/[TABLENAME]/edit',['id'=>$vo['id']])}" class="btn btn-mini btn-warning">编辑</a>
                                        <button name="del" action="{:url('[MODULE]/[TABLENAME]/delete',['id'=>$vo['id']])}" class="btn btn-mini btn-danger">删除</button>
                                    </td>
                                </tr>
                            {/volist}
                            </tbody>
                        </table></div>
                    <!--分页-->
                    {$list->render()}
                </div>
        </div>
    </div>
</div>
{/block}
{block name="script"}
<script>
    $(function(){
        $('.order-th-btn').click(function(){
            $name=$(this).attr('sort');
            $sorts=$('.sort');
            $sorts.each(function(){
                if($(this).attr('sort') !=$name){
                    $(this).remove();
                }
            });
            $("form").submit();
        });
    });
</script>
{/block}
