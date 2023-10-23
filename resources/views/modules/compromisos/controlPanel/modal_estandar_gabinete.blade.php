<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <label>Fecha inicio del compromiso:</label>
            <div class="input-group">
                <input type="date" value="{{ $fecha_inicio }}" class="form-control form-control-sm"
                    id="fecha_inicio_gabinete">
                - <input type="date" value="<?php echo date('Y-12-31'); ?>" class="form-control form-control-sm"
                    id="fecha_fin_gabinete">

            </div>
        </div>
        <div class="col-md-12">
            <label>Gabinete Sectorial</label>
            {!! Form::select('gabinete', $cqlGabinete, null, [
                'id' => 'filtro_gab',
                'class' => 'form-control select2',
                'placeholder' => 'TODOS LOS GABINETES',
                'multiple' => 'multiple',
            ]) !!}
        </div>
    </div>
</div>
