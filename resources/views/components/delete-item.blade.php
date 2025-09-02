<!-- Delete Button -->
<a href="{{ $url }}" data-id="{{ $id }}" 
   class="px-3 py-1 text-white bg-red-600 hover:bg-red-700 rounded delete">
   <i class="fa-regular fa-trash-can"></i>
</a>

<!-- Modal -->
<div id="deleteModal" 
     class="fixed inset-0 hidden bg-black/85 flex items-center justify-center z-50" dir="ltr">
  
  <!-- Modal Box -->
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md text-right">
    
    <!-- Header -->
    <div class="flex justify-between items-center border-b dark:border-gray-600 px-4 py-2">
      <h2 class="text-lg font-bold text-red-600 dark:text-red-400">Warning!</h2>
      <button type="button" onclick="closeModal()" 
              class="text-gray-600 dark:text-gray-300 hover:text-red-600 text-3xl leading-none">
        &times;
      </button>
    </div>
    
    <!-- Form -->
    <form id="deleteForm" action="{{ $url }}" method="post" class="p-4">
      @csrf
      @method('DELETE')
      <input id="itemId" name="id" hidden />

      <p class="text-center text-gray-700 dark:text-gray-300 mb-4">
        This data will be permanently deleted from the system. Are you sure?
      </p>

      <!-- Footer -->
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeModal()"
                class="px-4 py-2 rounded bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-500">
          Cancel
        </button>
        <button type="submit"
                class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">
          Yes, Delete
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Script -->
<script>
  // Open modal
  document.addEventListener('click', function(e) {
    if (e.target.closest('.delete')) {
      e.preventDefault();
      const btn = e.target.closest('.delete');
      const id = btn.dataset.id;
      const url = btn.getAttribute('href');
      document.getElementById('itemId').value = id;
      document.getElementById('deleteForm').setAttribute('action', url);
      document.getElementById('deleteModal').classList.remove('hidden');
    }
  });

  // Close modal
  function closeModal() {
    document.getElementById('deleteModal').classList.add('hidden');
  }

  // Close when clicking outside modal box
  document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
  });
</script>
