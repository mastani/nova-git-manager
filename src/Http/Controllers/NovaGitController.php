<?php

namespace Mastani\NovaGitManager\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class NovaGitController extends BaseController
{

    private function execGitCommand($command)
    {
        return shell_exec('cd ' . config('nova-git-manager.git_path', base_path()) . ' && git ' . $command . ' 2>&1');
    }

    public function branches()
    {
        $this->execGitCommand('fetch');
        $output = $this->execGitCommand('branch -r');
        $output = Str::replace(["\n", "\r\n", '*'], '', $output);
        $output = Str::replace('  ', ' ', $output);
        $output = trim($output);

        $branches = explode(' ', $output);

        return response()->json([
            'branches' => $branches
        ]);
    }

    public function log()
    {
        $output = $this->execGitCommand('log --pretty=format:\'{
            @@refs@@: @@%D@@,
            @@parents_as_string@@: @@%P@@,
            @@id@@: @@%H@@,
            @@message@@: @@%s@@,
            @@date@@: @@%aI@@,
            @@author@@: {
                @@name@@: @@%an@@,
                @@email@@: @@%ae@@
            }
        },\' --all');

        $output = Str::replace('\\', "\\\\", $output);
        $output = Str::replace('"', '\"', $output);
        $output = Str::replace('@@', '"', $output);
        $output = '[' . rtrim($output, ",") . ']';

        $spaces = [];
        $lastSpace = 1;
        $time = 0;

        $data = [];
        $data['days'] = [];
        $data['commits'] = json_decode($output, true);
        $data['commits'] = collect($data['commits'])->map(function ($commit) use (&$time, &$spaces, &$lastSpace, &$data) {

            // Format Refs
            $formatted_refs = preg_replace("/,|HEAD|->|tag:|origin\//", ' ', $commit['refs']);
            $formatted_refs = preg_replace("/\s+/", ' ', $formatted_refs);
            $formatted_refs = trim($formatted_refs);
            $formatted_refs = explode(' ', $formatted_refs);
            $commit['refs'] = end($formatted_refs);

            // Format Parents
            $lastSpace = in_array($commit['id'], $spaces) ? $spaces[$commit['id']] : 1;
            $commit['space'] = $lastSpace;

            $commit['parents'] = [];
            collect(explode(' ', $commit['parents_as_string']))->each(function ($parent) use (&$commit, &$spaces, &$lastSpace) {
                $commit['parents'][] = [$parent, $lastSpace];

                if (!in_array($parent, $spaces))
                    $spaces[$parent] = $lastSpace;
                else
                    $spaces[$parent] = $lastSpace - $commit['space'] + 1;

                $lastSpace = $lastSpace + 2;
            });
            unset($commit['parents_as_string']);

            $commit['author']['icon'] = 'https://secure.gravatar.com/avatar/' . md5($commit['author']['email']) . '?s=40&d=wavatar';
            $commit['date'] = Str::replace('+', '.000+', $commit['date']);
            $commit['time'] = $time;
            $time++;

            $date = strtotime($commit['date']);
            $data['days'][] = [(int)date("d", $date), date("M", $date), (int)date("Y", $date)];

            return $commit;
        });

        Storage::disk('public')->put('nova-git-data.json', json_encode($data));
        $url = Storage::disk('public')->url('nova-git-data.json');

        return response()->json([
            'data_url' => $url,
            'commit_url' => config('nova-git-manager.commit_url')
        ]);
    }

    public function pull(Request $request)
    {
        if (!$request->has('branch'))
            return response()->json([
                'success' => false,
                'message' => 'Branch not selected'
            ]);

        $branch = Str::replace('/', ' ', $request->input('branch'));
        $output = $this->execGitCommand('pull ' . $branch);

        return response()->json([
            'success' => !Str::contains($output, 'fatal'),
            'message' => $output
        ]);
    }

    public function checkout(Request $request)
    {
        if (!$request->has('branch'))
            return response()->json([
                'success' => false,
                'message' => 'Branch not selected'
            ]);

        $branch = $request->input('branch');
        $output = $this->execGitCommand('checkout ' . $branch);

        return response()->json([
            'success' => !Str::contains($output, 'fatal'),
            'message' => $output
        ]);
    }
}
