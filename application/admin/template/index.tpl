{extend name="./data/tpl/base.html" /}
{block name="body"}
<div class="container-fluid">
    <div class="container-fluid">
        <ul class="nav nav-tabs">
            <li class="active"><a>[TABLECOMMENT]管理</a></li>
            <li><a href="{:url('[MODULE]/[TABLENAME]/add')}">添加[TABLECOMMENT]</a></li>
        </ul>
        <div class="container-fluid">
            <form action="" method="post" class="ajax-form">
                <div class="container-fluid">
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
                                        <a href="{:url('[MODULE]/[TABLENAME]/delete',['id'=>$vo['id']])}" class="btn btn-mini btn-danger">删除</a>
                                    </td>
                                </tr>
                            {/volist}
                            </tbody>
                        </table></div>
                    <!--分页-->
                    {$list->render()}
                </div>
            </form>
        </div>
    </div>
</div>
{/block}

