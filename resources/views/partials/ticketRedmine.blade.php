<form action="{{ route('tickets.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="subject">Sujet:</label>
        <input type="text" id="subject" name="subject" class="form-control" required>
    </div>
    <div class="form-group mt-3">
        <label for="description">Description:</label>
        <textarea id="description" name="description" class="form-control" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Créer Ticket</button>
</form>
