# Git & GitHub Industry-Level Commands Practice



## 1. Git Configuration Commands

### `git config --global user.name`

| Field | Details |
|---|---|
| **Syntax** | `git config --global user.name "Your Name"` |
| **Purpose** | Sets the global username associated with all your Git commits |
| **Example** | `git config --global user.name "mouli-220383"



---

### `git config --global user.email`

| Field | Details |
|---|---|
| **Syntax** | `git config --global user.email "you@example.com"` |
| **Purpose** | Sets the global email address associated with all your Git commits |
| **Example** | `git config --global user.email "n220383@rguktn.ac.in



---

### `git config --list`

| Field | Details |
|---|---|
| **Syntax** | `git config --list` |
| **Purpose** | Lists all Git configuration settings currently in effect |
| **Example** | `git config --list` |



---

### `git config --unset`

| Field | Details |
|---|---|
| **Syntax** | `git config --global --unset <key>` |
| **Purpose** | Removes a specific configuration key from the config file |
| **Example** | `git config --global --unset user.name` |



---

## 2. Repository Setup Commands

### `git init`

| Field | Details |
|---|---|
| **Syntax** | `git init [directory]` |
| **Purpose** | Initializes a new empty Git repository in the current or specified directory |
| **Example** | `git init library`|

---

### `git clone`

| Field | Details |
|---|---|
| **Syntax** | `git clone <repository-url> [directory]` |
| **Purpose** | Creates a local copy of a remote repository |
| **Example** | `git clone https://github.com/mouli-220383/WT_LAB.git`|



---

### `git clone --branch`

| Field | Details |
|---|---|
| **Syntax** | `git clone --branch <branch-name> <repository-url>` |
| **Purpose** | Clones a specific branch from a remote repository |
| **Example** | `git clone --branch develop https://github.com/mouli-220383/WT_LAB.git`|


---

### `git clone --depth`

| Field | Details |
|---|---|
| **Syntax** | `git clone --depth <number> <repository-url>` |
| **Purpose** | Creates a shallow clone with a limited commit history (faster, smaller) |
| **Example** | `git clone --depth 1 https://github.com/mouli-220383/WT_LAB.git`|


---

## 3. Repository Status & Inspection

### `git status`

| Field | Details |
|---|---|
| **Syntax** | `git status` |
| **Purpose** | Shows the current state of the working directory and staging area |
| **Example** | `git status` |



---

### `git log`

| Field | Details |
|---|---|
| **Syntax** | `git log [options]` |
| **Purpose** | Shows the full commit history of the repository |
| **Example** | `git log` |



---

### `git log --oneline`

| Field | Details |
|---|---|
| **Syntax** | `git log --oneline` |
| **Purpose** | Displays a condensed one-line summary of each commit |
| **Example** | `git log --oneline` |



---

### `git log --graph`

| Field | Details |
|---|---|
| **Syntax** | `git log --graph --oneline --all` |
| **Purpose** | Displays a visual ASCII graph of the branch and merge history |
| **Example** | `git log --graph --oneline --all` |



---

### `git show`

| Field | Details |
|---|---|
| **Syntax** | `git show <commit-hash>` |
| **Purpose** | Shows information and changes introduced by a specific commit |
| **Example** | `git show a1b2c3d` |



---

### `git diff`

| Field | Details |
|---|---|
| **Syntax** | `git diff [file]` |
| **Purpose** | Shows unstaged changes between your working directory and the last commit |
| **Example** | `git diff index.html` |



---

### `git diff --staged`

| Field | Details |
|---|---|
| **Syntax** | `git diff --staged` |
| **Purpose** | Shows changes that are staged and ready to be committed |
| **Example** | `git diff --staged` |



---

### `git blame`

| Field | Details |
|---|---|
| **Syntax** | `git blame <file>` |
| **Purpose** | Shows who last modified each line of a file and when |
| **Example** | `git blame README.md` |



---

### `git reflog`

| Field | Details |
|---|---|
| **Syntax** | `git reflog` |
| **Purpose** | Records all changes to HEAD, useful for recovering lost commits |
| **Example** | `git reflog` |



---

### `git shortlog`

| Field | Details |
|---|---|
| **Syntax** | `git shortlog` |
| **Purpose** | Summarizes commit history grouped by author |
| **Example** | `git shortlog` |



---

## 4. File Tracking Commands

### `git add`

| Field | Details |
|---|---|
| **Syntax** | `git add <file>` |
| **Purpose** | Stages a specific file for the next commit |
| **Example** | `git add index.html` |



---

### `git add .`

| Field | Details |
|---|---|
| **Syntax** | `git add .` |
| **Purpose** | Stages all changed files in the current directory and subdirectories |
| **Example** | `git add .` |



---

### `git add -p`

| Field | Details |
|---|---|
| **Syntax** | `git add -p [file]` |
| **Purpose** | Interactively stages parts (hunks) of a file — useful for partial staging |
| **Example** | `git add -p app.js` |



---

### `git restore`

| Field | Details |
|---|---|
| **Syntax** | `git restore <file>` |
| **Purpose** | Discards changes in the working directory (restores file to last commit) |
| **Example** | `git restore index.html` |



---

### `git restore --staged`

| Field | Details |
|---|---|
| **Syntax** | `git restore --staged <file>` |
| **Purpose** | Removes a file from the staging area without discarding changes |
| **Example** | `git restore --staged index.html` |


---

### `git rm`

| Field | Details |
|---|---|
| **Syntax** | `git rm <file>` |
| **Purpose** | Removes a file from both the working directory and the staging area |
| **Example** | `git rm old-file.txt` |

 

---

### `git mv`

| Field | Details |
|---|---|
| **Syntax** | `git mv <old-name> <new-name>` |
| **Purpose** | Renames or moves a file and stages the change |
| **Example** | `git mv old-name.txt new-name.txt` |

 

---

## 5. Commit Commands

### `git commit`

| Field | Details |
|---|---|
| **Syntax** | `git commit` |
| **Purpose** | Opens the default text editor to write a commit message and saves the staged changes |
| **Example** | `git commit` |

 

---

### `git commit -m`

| Field | Details |
|---|---|
| **Syntax** | `git commit -m "message"` |
| **Purpose** | Commits staged changes with an inline message — most commonly used |
| **Example** | `git commit -m "Add login feature"` |

 

---

### `git commit --amend`

| Field | Details |
|---|---|
| **Syntax** | `git commit --amend` |
| **Purpose** | Modifies the most recent commit (message or content) |
| **Example** | `git commit --amend -m "Fixed typo in login feature"` |

 

---

### `git commit --no-edit`

| Field | Details |
|---|---|
| **Syntax** | `git commit --amend --no-edit` |
| **Purpose** | Amends the last commit without changing its commit message |
| **Example** | `git commit --amend --no-edit` |

 

---

## 6. Branch Management Commands

### `git branch`

| Field | Details |
|---|---|
| **Syntax** | `git branch` |
| **Purpose** | Lists all local branches; highlights the current branch |
| **Example** | `git branch` |

 

---

### `git branch -a`

| Field | Details |
|---|---|
| **Syntax** | `git branch -a` |
| **Purpose** | Lists all local and remote branches |
| **Example** | `git branch -a` |

 

---

### `git branch -d`

| Field | Details |
|---|---|
| **Syntax** | `git branch -d <branch-name>` |
| **Purpose** | Deletes a branch safely (only if already merged) |
| **Example** | `git branch -d feature/login` |

 

---

### `git branch -D`

| Field | Details |
|---|---|
| **Syntax** | `git branch -D <branch-name>` |
| **Purpose** | Force-deletes a branch regardless of merge status |
| **Example** | `git branch -D feature/login` |

 

---

### `git checkout`

| Field | Details |
|---|---|
| **Syntax** | `git checkout <branch-name>` |
| **Purpose** | Switches to an existing branch |
| **Example** | `git checkout main` |

 

---

### `git checkout -b`

| Field | Details |
|---|---|
| **Syntax** | `git checkout -b <new-branch-name>` |
| **Purpose** | Creates and immediately switches to a new branch |
| **Example** | `git checkout -b feature/signup` |

 

---

### `git switch`

| Field | Details |
|---|---|
| **Syntax** | `git switch <branch-name>` |
| **Purpose** | Modern alternative to `git checkout` for switching branches |
| **Example** | `git switch main` |

 

---

### `git switch -c`

| Field | Details |
|---|---|
| **Syntax** | `git switch -c <new-branch-name>` |
| **Purpose** | Creates and switches to a new branch (modern syntax) |
| **Example** | `git switch -c feature/dashboard` |

 

---

## 7. Merge & Integration Commands

### `git merge`

| Field | Details |
|---|---|
| **Syntax** | `git merge <branch-name>` |
| **Purpose** | Integrates changes from another branch into the current branch |
| **Example** | `git merge feature/login` |

 

---

### `git merge --no-ff`

| Field | Details |
|---|---|
| **Syntax** | `git merge --no-ff <branch-name>` |
| **Purpose** | Forces a merge commit even if a fast-forward merge is possible, preserving branch history |
| **Example** | `git merge --no-ff feature/login` |

 

---

## 8. Remote Repository Commands

### `git remote`

| Field | Details |
|---|---|
| **Syntax** | `git remote` |
| **Purpose** | Lists all remote connections |
| **Example** | `git remote` |

 

---

### `git remote -v`

| Field | Details |
|---|---|
| **Syntax** | `git remote -v` |
| **Purpose** | Shows remote names along with their URLs (fetch and push) |
| **Example** | `git remote -v` |

 

---

### `git remote add`

| Field | Details |
|---|---|
| **Syntax** | `git remote add <name> <url>` |
| **Purpose** | Adds a new remote repository connection |
| **Example** | `git remote add origin https://github.com/username/repo.git` |

 

---

### `git remote remove`

| Field | Details |
|---|---|
| **Syntax** | `git remote remove <name>` |
| **Purpose** | Removes a remote connection |
| **Example** | `git remote remove origin` |

 

---

### `git fetch`

| Field | Details |
|---|---|
| **Syntax** | `git fetch <remote>` |
| **Purpose** | Downloads changes from a remote without merging them |
| **Example** | `git fetch origin` |

 

---

### `git fetch --all`

| Field | Details |
|---|---|
| **Syntax** | `git fetch --all` |
| **Purpose** | Fetches changes from all configured remotes |
| **Example** | `git fetch --all` |

 

---

### `git pull`

| Field | Details |
|---|---|
| **Syntax** | `git pull <remote> <branch>` |
| **Purpose** | Fetches and merges changes from the remote branch into the current branch |
| **Example** | `git pull origin main` |

 

---

### `git pull --rebase`

| Field | Details |
|---|---|
| **Syntax** | `git pull --rebase <remote> <branch>` |
| **Purpose** | Fetches remote changes and rebases your local commits on top (cleaner history) |
| **Example** | `git pull --rebase origin main` |

 

---

### `git push`

| Field | Details |
|---|---|
| **Syntax** | `git push <remote> <branch>` |
| **Purpose** | Uploads local commits to the remote repository |
| **Example** | `git push origin main` |

 

---

### `git push -u origin branch-name`

| Field | Details |
|---|---|
| **Syntax** | `git push -u origin <branch-name>` |
| **Purpose** | Pushes branch to remote and sets it as the default upstream for future pushes |
| **Example** | `git push -u origin feature/login` |

 

---

### `git push --force`

| Field | Details |
|---|---|
| **Syntax** | `git push --force` |
| **Purpose** | Force-pushes local branch to remote, overwriting remote history (**use with caution**) |
| **Example** | `git push --force origin feature/login` |

> ⚠️ **Warning:** `--force` can overwrite others' work. Prefer `--force-with-lease` in team environments.

 

---

## 9. Stash Commands

### `git stash`

| Field | Details |
|---|---|
| **Syntax** | `git stash` |
| **Purpose** | Temporarily saves uncommitted changes so you can switch contexts |
| **Example** | `git stash` |

 

---

### `git stash list`

| Field | Details |
|---|---|
| **Syntax** | `git stash list` |
| **Purpose** | Lists all stashed changesets |
| **Example** | `git stash list` |

 

---

### `git stash pop`

| Field | Details |
|---|---|
| **Syntax** | `git stash pop` |
| **Purpose** | Applies the most recent stash and removes it from the stash list |
| **Example** | `git stash pop` |

 

---

### `git stash apply`

| Field | Details |
|---|---|
| **Syntax** | `git stash apply [stash@{n}]` |
| **Purpose** | Applies a stash without removing it from the stash list |
| **Example** | `git stash apply stash@{1}` |

 

---

### `git stash drop`

| Field | Details |
|---|---|
| **Syntax** | `git stash drop [stash@{n}]` |
| **Purpose** | Deletes a specific stash entry from the list |
| **Example** | `git stash drop stash@{0}` |

 

---

### `git stash clear`

| Field | Details |
|---|---|
| **Syntax** | `git stash clear` |
| **Purpose** | Removes all stash entries permanently |
| **Example** | `git stash clear` |

 

---

## 10. Reset & Undo Commands

### `git reset`

| Field | Details |
|---|---|
| **Syntax** | `git reset <commit>` |
| **Purpose** | Moves HEAD to a previous commit (default: mixed mode) |
| **Example** | `git reset HEAD~1` |

 

---

### `git reset --soft`

| Field | Details |
|---|---|
| **Syntax** | `git reset --soft <commit>` |
| **Purpose** | Moves HEAD back but keeps changes staged |
| **Example** | `git reset --soft HEAD~1` |

 

---

### `git reset --mixed`

| Field | Details |
|---|---|
| **Syntax** | `git reset --mixed <commit>` |
| **Purpose** | Moves HEAD back and unstages changes, but keeps them in the working directory (default behavior) |
| **Example** | `git reset --mixed HEAD~1` |

 

---

### `git reset --hard`

| Field | Details |
|---|---|
| **Syntax** | `git reset --hard <commit>` |
| **Purpose** | Moves HEAD back and **discards all changes** in staging and working directory |
| **Example** | `git reset --hard HEAD~1` |

> ⚠️ **Warning:** This permanently deletes uncommitted changes.

 

---

### `git revert`

| Field | Details |
|---|---|
| **Syntax** | `git revert <commit-hash>` |
| **Purpose** | Creates a new commit that undoes the changes of a specific commit (safe for shared branches) |
| **Example** | `git revert a1b2c3d` |

 

---

### `git clean -f`

| Field | Details |
|---|---|
| **Syntax** | `git clean -f` |
| **Purpose** | Removes untracked files from the working directory |
| **Example** | `git clean -f` |

 

---

### `git clean -fd`

| Field | Details |
|---|---|
| **Syntax** | `git clean -fd` |
| **Purpose** | Removes untracked files **and directories** from the working directory |
| **Example** | `git clean -fd` |

 

---

## 11. Rebasing Commands

### `git rebase`

| Field | Details |
|---|---|
| **Syntax** | `git rebase <base-branch>` |
| **Purpose** | Moves or replays your commits onto a different base branch |
| **Example** | `git rebase main` |

 

---

### `git rebase -i`

| Field | Details |
|---|---|
| **Syntax** | `git rebase -i HEAD~<n>` |
| **Purpose** | Interactive rebase — allows squashing, reordering, editing, or dropping commits |
| **Example** | `git rebase -i HEAD~3` |

 

---

### `git rebase --continue`

| Field | Details |
|---|---|
| **Syntax** | `git rebase --continue` |
| **Purpose** | Continues a rebase after resolving merge conflicts |
| **Example** | `git rebase --continue` |

 

---

### `git rebase --abort`

| Field | Details |
|---|---|
| **Syntax** | `git rebase --abort` |
| **Purpose** | Cancels an in-progress rebase and restores the original branch state |
| **Example** | `git rebase --abort` |

 

---

## 12. Cherry Pick & Patch Commands

### `git cherry-pick`

| Field | Details |
|---|---|
| **Syntax** | `git cherry-pick <commit-hash>` |
| **Purpose** | Applies the changes from a specific commit onto the current branch |
| **Example** | `git cherry-pick a1b2c3d` |

 

---

### `git format-patch`

| Field | Details |
|---|---|
| **Syntax** | `git format-patch <since-commit>` |
| **Purpose** | Creates patch files for commits, useful for sharing changes via email |
| **Example** | `git format-patch HEAD~3` |

 

---

### `git apply`

| Field | Details |
|---|---|
| **Syntax** | `git apply <patch-file>` |
| **Purpose** | Applies a patch file to the working directory without committing |
| **Example** | `git apply fix.patch` |

 

---

### `git am`

| Field | Details |
|---|---|
| **Syntax** | `git am <patch-file>` |
| **Purpose** | Applies a patch file **and** creates a commit automatically (from `format-patch` output) |
| **Example** | `git am 0001-fix-login.patch` |