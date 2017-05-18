{extend name="./data/tpl/base.html" /}
{block name="body"}
    <div class="container-fluid">
        <!--tab标签-->
        <div class="container-fluid">
            <ul class="nav nav-tabs">
                <li><a href="{:url('[MODULE]/[TABLENAME]/index')}">[TABLECOMMENT]管理</a></li>
                <li class="active"><a>添加[TABLECOMMENT]</a></li>
            </ul>
            <div class="container-fluid">
                <div class="container-fluid">
                    <form class="form-horizontal" action="{:url('[MODULE]/[TABLENAME]/save')}" method="post">
                       [ROWS]
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">保存</button>
                                <button type="reset" class="btn btn-default">重置</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
{/block}