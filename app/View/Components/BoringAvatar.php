<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BoringAvatar extends Component
{
    public $name;
    public $size;
    public $colors;

    public function __construct($name = 'User', $size = 40, $colors = 'd0f849,f8f149,49f8b4,49f8f1,a589fb')
    {
        $this->name = $name;
        $this->size = $size;
        $this->colors = $colors;
    }

    private function getSvgForName($name)
    {
        // Calculate a deterministic index from 0 to 9 based on the name characters
        $sum = 0;
        for ($i = 0; $i < strlen($name); $i++) {
            $sum += ord($name[$i]);
        }
        $index = $sum % 10;
        
        // Use a unique suffix for the mask ID to prevent bleeding between multiple SVGs on the same page
        $maskId = '_m_' . substr(md5($name), 0, 8) . '_';
        
        $svgs = [
            '<svg viewBox="0 0 36 36" fill="none" role="img" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%"><mask id="%MASK%" maskUnits="userSpaceOnUse" x="0" y="0" width="36" height="36"><rect width="36" height="36" fill="#FFFFFF"></rect></mask><g mask="url(#%MASK%)"><rect width="36" height="36" fill="#49f8f1"></rect><rect x="0" y="0" width="36" height="36" transform="translate(4 4) rotate(340 18 18) scale(1.1)" fill="#d0f849" rx="36"></rect><g transform="translate(-4 -1) rotate(0 18 18)"><path d="M15 20c2 1 4 1 6 0" stroke="#000000" fill="none" stroke-linecap="round"></path><rect x="14" y="14" width="1.5" height="2" rx="1" stroke="none" fill="#000000"></rect><rect x="20" y="14" width="1.5" height="2" rx="1" stroke="none" fill="#000000"></rect></g></g></svg>',
            '<svg viewBox="0 0 36 36" fill="none" role="img" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%"><mask id="%MASK%" maskUnits="userSpaceOnUse" x="0" y="0" width="36" height="36"><rect width="36" height="36" fill="#FFFFFF"></rect></mask><g mask="url(#%MASK%)"><rect width="36" height="36" fill="#49f8b4"></rect><rect x="0" y="0" width="36" height="36" transform="translate(0 0) rotate(324 18 18) scale(1)" fill="#a589fb" rx="36"></rect><g transform="translate(-4 -4) rotate(-4 18 18)"><path d="M15 19c2 1 4 1 6 0" stroke="#000000" fill="none" stroke-linecap="round"></path><rect x="10" y="14" width="1.5" height="2" rx="1" stroke="none" fill="#000000"></rect><rect x="24" y="14" width="1.5" height="2" rx="1" stroke="none" fill="#000000"></rect></g></g></svg>',
            '<svg viewBox="0 0 36 36" fill="none" role="img" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%"><mask id="%MASK%" maskUnits="userSpaceOnUse" x="0" y="0" width="36" height="36"><rect width="36" height="36" fill="#FFFFFF"></rect></mask><g mask="url(#%MASK%)"><rect width="36" height="36" fill="#49f8f1"></rect><rect x="0" y="0" width="36" height="36" transform="translate(5 -1) rotate(155 18 18) scale(1.2)" fill="#d0f849" rx="6"></rect><g transform="translate(3 -4) rotate(-5 18 18)"><path d="M15 21c2 1 4 1 6 0" stroke="#000000" fill="none" stroke-linecap="round"></path><rect x="14" y="14" width="1.5" height="2" rx="1" stroke="none" fill="#000000"></rect><rect x="20" y="14" width="1.5" height="2" rx="1" stroke="none" fill="#000000"></rect></g></g></svg>',
            '<svg viewBox="0 0 36 36" fill="none" role="img" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%"><mask id="%MASK%" maskUnits="userSpaceOnUse" x="0" y="0" width="36" height="36"><rect width="36" height="36" fill="#FFFFFF"></rect></mask><g mask="url(#%MASK%)"><rect width="36" height="36" fill="#a589fb"></rect><rect x="0" y="0" width="36" height="36" transform="translate(3 5) rotate(301 18 18) scale(1.1)" fill="#f8f149" rx="36"></rect><g transform="translate(-5 3) rotate(-1 18 18)"><path d="M13,20 a1,0.75 0 0,0 10,0" fill="#000000"></path><rect x="13" y="14" width="1.5" height="2" rx="1" stroke="none" fill="#000000"></rect><rect x="21" y="14" width="1.5" height="2" rx="1" stroke="none" fill="#000000"></rect></g></g></svg>',
            '<svg viewBox="0 0 36 36" fill="none" role="img" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%"><mask id="%MASK%" maskUnits="userSpaceOnUse" x="0" y="0" width="36" height="36"><rect width="36" height="36" fill="#FFFFFF"></rect></mask><g mask="url(#%MASK%)"><rect width="36" height="36" fill="#a589fb"></rect><rect x="0" y="0" width="36" height="36" transform="translate(6 6) rotate(356 18 18) scale(1.2)" fill="#f8f149" rx="6"></rect><g transform="translate(4 1) rotate(6 18 18)"><path d="M13,21 a1,0.75 0 0,0 10,0" fill="#000000"></path><rect x="13" y="14" width="1.5" height="2" rx="1" stroke="none" fill="#000000"></rect><rect x="21" y="14" width="1.5" height="2" rx="1" stroke="none" fill="#000000"></rect></g></g></svg>',
            '<svg viewBox="0 0 36 36" fill="none" role="img" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%"><mask id="%MASK%" maskUnits="userSpaceOnUse" x="0" y="0" width="36" height="36"><rect width="36" height="36" fill="#FFFFFF"></rect></mask><g mask="url(#%MASK%)"><rect width="36" height="36" fill="#f8f149"></rect><rect x="0" y="0" width="36" height="36" transform="translate(-4 8) rotate(168 18 18) scale(1)" fill="#49f8f1" rx="36"></rect><g transform="translate(0 4) rotate(-8 18 18)"><path d="M13,19 a1,0.75 0 0,0 10,0" fill="#000000"></path><rect x="11" y="14" width="1.5" height="2" rx="1" stroke="none" fill="#000000"></rect><rect x="23" y="14" width="1.5" height="2" rx="1" stroke="none" fill="#000000"></rect></g></g></svg>',
            '<svg viewBox="0 0 36 36" fill="none" role="img" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%"><mask id="%MASK%" maskUnits="userSpaceOnUse" x="0" y="0" width="36" height="36"><rect width="36" height="36" fill="#FFFFFF"></rect></mask><g mask="url(#%MASK%)"><rect width="36" height="36" fill="#49f8b4"></rect><rect x="0" y="0" width="36" height="36" transform="translate(9 -5) rotate(219 18 18) scale(1)" fill="#a589fb" rx="6"></rect><g transform="translate(4.5 -4) rotate(9 18 18)"><path d="M15 19c2 1 4 1 6 0" stroke="#000000" fill="none" stroke-linecap="round"></path><rect x="10" y="14" width="1.5" height="2" rx="1" stroke="none" fill="#000000"></rect><rect x="24" y="14" width="1.5" height="2" rx="1" stroke="none" fill="#000000"></rect></g></g></svg>',
            '<svg viewBox="0 0 36 36" fill="none" role="img" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%"><mask id="%MASK%" maskUnits="userSpaceOnUse" x="0" y="0" width="36" height="36"><rect width="36" height="36" fill="#FFFFFF"></rect></mask><g mask="url(#%MASK%)"><rect width="36" height="36" fill="#d0f849"></rect><rect x="0" y="0" width="36" height="36" transform="translate(7 7) rotate(37 18 18) scale(1.1)" fill="#49f8b4" rx="6"></rect><g transform="translate(3.5 3.5) rotate(-7 18 18)"><path d="M13,20 a1,0.75 0 0,0 10,0" fill="#000000"></path><rect x="12" y="14" width="1.5" height="2" rx="1" stroke="none" fill="#000000"></rect><rect x="22" y="14" width="1.5" height="2" rx="1" stroke="none" fill="#000000"></rect></g></g></svg>',
            '<svg viewBox="0 0 36 36" fill="none" role="img" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%"><mask id="%MASK%" maskUnits="userSpaceOnUse" x="0" y="0" width="36" height="36"><rect width="36" height="36" fill="#FFFFFF"></rect></mask><g mask="url(#%MASK%)"><rect width="36" height="36" fill="#d0f849"></rect><rect x="0" y="0" width="36" height="36" transform="translate(2 2) rotate(142 18 18) scale(1.1)" fill="#49f8b4" rx="36"></rect><g transform="translate(-6 -5) rotate(2 18 18)"><path d="M15 20c2 1 4 1 6 0" stroke="#000000" fill="none" stroke-linecap="round"></path><rect x="12" y="14" width="1.5" height="2" rx="1" stroke="none" fill="#000000"></rect><rect x="22" y="14" width="1.5" height="2" rx="1" stroke="none" fill="#000000"></rect></g></g></svg>',
            '<svg viewBox="0 0 36 36" fill="none" role="img" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%"><mask id="%MASK%" maskUnits="userSpaceOnUse" x="0" y="0" width="36" height="36"><rect width="36" height="36" fill="#FFFFFF"></rect></mask><g mask="url(#%MASK%)"><rect width="36" height="36" fill="#d0f849"></rect><rect x="0" y="0" width="36" height="36" transform="translate(-3 7) rotate(227 18 18) scale(1.2)" fill="#49f8b4" rx="36"></rect><g transform="translate(-3 3.5) rotate(7 18 18)"><path d="M13,21 a1,0.75 0 0,0 10,0" fill="#000000"></path><rect x="12" y="14" width="1.5" height="2" rx="1" stroke="none" fill="#000000"></rect><rect x="22" y="14" width="1.5" height="2" rx="1" stroke="none" fill="#000000"></rect></g></g></svg>'
        ];
        
        return str_replace('%MASK%', $maskId, $svgs[$index]);
    }

    public function render(): View|Closure|string
    {
        $svg = collect([$this])->map(function() {
            return $this->getSvgForName($this->name);
        })->first();

        return view('components.boring-avatar', ['svg' => $svg]);
    }
}
