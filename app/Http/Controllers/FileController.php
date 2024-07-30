<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\View\View
    {
        $files = File::orderBy("updated_at", "desc")->paginate(10);
        return view("file.index", compact("files"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Illuminate\View\View
    {
        return view('file.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'file' => 'required|file',
            ]);

            $path = $request->file('file')->store('files');

            $file = new File();
            $file->name = $validatedData['name'];
            $file->user_id = Auth::id();
            $file->size = $request->file('file')->getSize();
            $file->path = $path;

            $file->save();

            return redirect()->route('files.index')->with('success', 'File uploaded successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('File upload failed: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(File $file): \Illuminate\View\View
    {
        return view("file.show", compact("file"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(File $file): \Illuminate\View\View
    {
        return view("file.edit", compact("file"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, File $file): \Illuminate\Http\RedirectResponse
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $file->name = $validatedData['name'];

            if ($request->hasFile('file')) {
                Storage::delete($file->path);
                $file->size = $request->file('file')->getSize();
                $file->path = $request->file('file')->store('files');
            }

            $file->save();

            return redirect()->route('files.index')->with('success', 'File updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('File update failed: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file): \Illuminate\Http\RedirectResponse
    {
        try {
            if (Storage::exists($file->path)) {
                Storage::delete($file->path);
            }
            $file->delete();

            return redirect()->route('files.index')->with('success', 'File deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('File deletion failed: ' . $e->getMessage());
        }
    }

    /**
     * Search for files.
     */
    public function search(Request $request): \Illuminate\Http\JsonResponse|\Illuminate\View\View
    {
        $queryBuilder = File::query();

        // Extract search, filterType, and starred from the request
        $search = $request->input('search');
        $filterType = $request->input('filterType');
        $starred = filter_var($request->input('starred'), FILTER_VALIDATE_BOOLEAN); // Convert to boolean

        // Apply search and filter criteria
        $this->applySearchAndFilters($queryBuilder, $search, $filterType, $starred);

        $files = $queryBuilder->orderBy('updated_at', 'desc')->paginate(10);

        if ($request->ajax()) {
            $output = $files->count() ? $this->renderTable($files) : 'No results';
            return response()->json(['html' => $output]);
        } else {
            return view('file.index', compact('files'));
        }
    }

    /**
     * Apply search and filtering criteria to the query.
     */
    private function applySearchAndFilters($queryBuilder, ?string $search, ?string $filterType, bool $starred = false)
    {
        if ($search) {
            $queryBuilder->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('CAST(id AS CHAR) LIKE ?', ['%' . $search . '%']);
            });
        }

        if ($filterType) {
            $queryBuilder->where('type', $filterType);
        }

        if ($starred) {
            $queryBuilder->where('starred', true);
        }
    }

    /**
     * Display a listing of the starred resource.
     */
    public function starred(Request $request): \Illuminate\Http\JsonResponse|\Illuminate\View\View
    {
        // Call search with starred = true
        return $this->search($request);
    }
    /**
     * Format file size in bytes, kilobytes, megabytes, etc.
     */
    private function formatFileSize(int $size): string
    {
        if ($size >= 1_000_000) {
            return number_format($size / 1_000_000, 2) . ' MB';
        } elseif ($size >= 1_000) {
            return number_format($size / 1_000, 2) . ' KB';
        } else {
            return $size . ' bytes';
        }
    }
    /**
     * Render the HTML table for the data.
     */
    private function renderTable(LengthAwarePaginator $data): string
    {
        $rows = $data->map(function ($file) {
            $starred = $file->starred ? ' ★' : '';
            return "
                <tr>
                    <td class='px-4 py-2 border-b border-gray-700'>
                        {$file->name}{$starred} {$file->type}
                    </td>
                    <td class='px-4 py-2 border-b border-gray-700'>{$file->user_id}</td>
                    <td class='px-4 py-2 border-b border-gray-700'>{$file->updated_at}</td>
                    <td class='px-4 py-2 border-b border-gray-700'>{$this->formatFileSize($file->size)}</td>
                    <td class='px-4 py-2 border-b border-gray-700 text-right pr-6'>
                        <div class='relative inline-block text-left'>
                            <button class='px-4 py-2 text-white hover:bg-zinc-800 rounded-full w-9 h-9 dropbtn' aria-expanded='true' aria-haspopup='true'>&#8285;</button>
                            <div class='absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-black shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none hidden' role='settings' aria-orientation='vertical' aria-labelledby='settings-button' tabindex='-1'>
                                <div class='py-1' role='none'>
                                    <a href='#' class='block px-4 py-2 text-sm hover:bg-zinc-700' role='settingsitem' tabindex='-1' id='menu-item-0'>Download</a>
                                    <a href='#' class='block px-4 py-2 text-sm hover:bg-zinc-700' role='settingsitem' tabindex='-1' id='menu-item-1'>Rename</a>
                                    <a href='#' class='block px-4 py-2 text-sm hover:bg-zinc-700' role='settingsitem' tabindex='-1' id='menu-item-2'>Delete</a>
                                    <a href='#' class='block px-4 py-2 text-sm hover:bg-zinc-700' role='settingsitem' tabindex='-1' id='menu-item-3'>Info</a>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>";
        })->implode('');

        return "
            <table class='w-full text-white border-collapse'>
                <thead>
                    <tr class='text-left'>
                        <th class='px-4 py-2 border-b border-gray-700'>Name</th>
                        <th class='px-4 py-2 border-b border-gray-700'>Owner</th>
                        <th class='px-4 py-2 border-b border-gray-700'>Last modified</th>
                        <th class='px-4 py-2 border-b border-gray-700'>File size</th>
                        <th class='px-4 py-2 border-b border-gray-700'></th>
                    </tr>
                </thead>
                <tbody>{$rows}</tbody>
            </table>";
    }
}
